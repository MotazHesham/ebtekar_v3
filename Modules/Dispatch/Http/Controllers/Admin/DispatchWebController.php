<?php

namespace Modules\Dispatch\Http\Controllers\Admin;

use App\Contracts\Shipping\CourierQueryContract;
use App\Contracts\Shipping\DispatchAssignmentContract;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Modules\Dispatch\Http\Requests\AssignCourierRequest;
use Modules\Dispatch\Http\Requests\AutoAssignCourierRequest;
use Modules\Dispatch\Http\Requests\BulkAssignCourierRequest;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Enums\ShipmentStatus;
use Modules\Shipping\Repositories\ShippingPartnerRepository;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DispatchWebController extends Controller
{
    public function __construct(
        protected DispatchAssignmentContract $dispatch,
        protected CourierQueryContract $couriers,
        protected ShippingPartnerRepository $partners,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('delivery_assign_courier'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Shipment::query()
                ->forUser()
                ->with(['shippingPartner', 'courier.user'])
                ->where('status', ShipmentStatus::ReceivedAtWarehouse->value)
                ->select(sprintf('%s.*', (new Shipment)->getTable()));

            if ($request->filled('shipping_partner_id')) {
                $query->where('shipping_partner_id', $request->shipping_partner_id);
            }
            if ($request->filled('governorate')) {
                $query->where('governorate', 'like', '%' . $request->governorate . '%');
            }

            $table = Datatables::of($query);
            $table->addColumn('select', fn ($row) => '<input type="checkbox" class="shipment-select" value="' . $row->id . '">');
            $table->editColumn('status', fn ($row) => '<span class="badge badge-info">' . e($row->status_label) . '</span>');
            $table->addColumn('partner_name', fn ($row) => $row->shippingPartner?->name ?? '-');
            $table->addColumn('courier_name', fn ($row) => $row->courier?->user?->name ?? '-');
            $table->editColumn('last_status_at', function ($row) {
                if (! $row->getRawOriginal('last_status_at')) {
                    return '-';
                }

                return Carbon::parse($row->getRawOriginal('last_status_at'))
                    ->format(config('panel.date_format') . ' ' . config('panel.time_format'));
            });
            $table->editColumn('remaining_cod', fn ($row) => number_format((float) $row->remaining_cod, 2));
            $table->rawColumns(['status', 'select']);

            return $table->make(true);
        }

        $partnerId = $request->integer('shipping_partner_id') ?: null;

        return view('dispatch::admin.index', [
            'shippingPartners' => $this->partners->activePluck(),
            'couriers'         => $this->couriers->eligibleForDispatch($partnerId),
            'courierLoads'     => $this->couriers->activeLoadCounts($partnerId),
            'queueCount'       => Shipment::query()->forUser()
                ->where('status', ShipmentStatus::ReceivedAtWarehouse->value)
                ->count(),
        ]);
    }

    public function assign(AssignCourierRequest $request)
    {
        $result = $this->dispatch->assignOne(
            (int) $request->shipment_id,
            (int) $request->courier_id
        );

        if ($request->expectsJson()) {
            return response()->json($result->toArray(), $result->ok ? 200 : 422);
        }

        toast($result->message, $result->ok ? 'success' : 'error');

        return back();
    }

    public function assignBulk(BulkAssignCourierRequest $request)
    {
        $batch = $this->dispatch->assignBulk(
            $request->input('shipment_ids'),
            (int) $request->courier_id
        );

        $message = __('dispatch::messages.batch_done', [
            'success' => $batch->success_count,
            'failed'  => $batch->failed_count,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'batch_id'      => $batch->id,
                'success_count' => $batch->success_count,
                'failed_count'  => $batch->failed_count,
                'message'       => $message,
            ]);
        }

        toast($message, $batch->success_count > 0 ? 'success' : 'error');

        return redirect()->route('admin.dispatch.index');
    }

    public function autoAssign(AutoAssignCourierRequest $request)
    {
        $batch = $this->dispatch->autoAssign(
            $request->input('shipment_ids'),
            auth()->id(),
            $request->input('shipping_partner_id') ? (int) $request->input('shipping_partner_id') : null
        );

        $message = __('dispatch::messages.batch_done', [
            'success' => $batch->success_count,
            'failed'  => $batch->failed_count,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'batch_id'      => $batch->id,
                'success_count' => $batch->success_count,
                'failed_count'  => $batch->failed_count,
                'message'       => $message,
            ]);
        }

        toast($message, $batch->success_count > 0 ? 'success' : 'error');

        return redirect()->route('admin.dispatch.index');
    }
}
