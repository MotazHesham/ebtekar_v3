<?php

namespace Modules\Shipping\Http\Controllers\Admin;

use App\Contracts\Shipping\ShipmentServiceContract;
use App\Contracts\Shipping\TimelineRecorderContract;
use App\Http\Controllers\Controller;
use Modules\Shipping\Http\Requests\MassDestroyShipmentRequest;
use Modules\Shipping\Http\Requests\StoreShipmentNoteRequest;
use Modules\Shipping\Http\Requests\UpdateShipmentRequest;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Modules\Courier\Entities\Courier;
use Modules\Courier\Repositories\CourierRepository;
use Modules\Returns\Enums\ReturnReason;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Entities\ShippingPartner;
use Modules\Shipping\Enums\ShipmentStatus;
use Modules\Shipping\Repositories\ShippingPartnerRepository;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ShipmentWebController extends Controller
{
    public function __construct(
        protected ShipmentServiceContract $shipmentService,
        protected TimelineRecorderContract $timeline,
        protected CourierRepository $couriers,
        protected ShippingPartnerRepository $partners,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('delivery_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Shipment::query()
                ->forUser()
                ->with(['shippingPartner', 'courier.user'])
                ->select(sprintf('%s.*', (new Shipment)->getTable()));

            foreach (['status', 'shipping_partner_id', 'deliver_man_id'] as $filter) {
                if ($request->filled($filter)) {
                    $query->where($filter, $request->input($filter));
                }
            }
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                return view('partials.datatablesActions', [
                    'viewGate'      => 'delivery_order_show',
                    'editGate'      => 'delivery_order_edit',
                    'deleteGate'    => 'delivery_order_delete',
                    'crudRoutePart' => 'delivery-orders',
                    'row'           => $row,
                ]);
            });
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
            $table->editColumn('pending_since', fn ($row) => $row->pending_since ?? '-');
            $table->editColumn('remaining_cod', fn ($row) => number_format((float) $row->remaining_cod, 2));
            $table->rawColumns(['actions', 'placeholder', 'status']);

            return $table->make(true);
        }

        return view('shipping::admin.deliveryOrders.index', [
            'shippingPartners' => $this->partners->activePluck(),
            'deliverMen'       => $this->couriers->activeWithUsers()->mapWithKeys(fn ($d) => [$d->id => $d->user?->name]),
            'dashboardStats'   => $this->dashboardStats(),
        ]);
    }

    public function show(Shipment $shipment)
    {
        abort_if(Gate::denies('delivery_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shipment->load([
            'orderable',
            'shippingPartner',
            'courier.user',
            'timelineEvents.user',
            'notes.user',
            'notes.replies.user',
        ]);

        $deliveryOrder = $shipment;

        return view('shipping::admin.deliveryOrders.show', [
            'deliveryOrder'    => $deliveryOrder,
            'statuses'         => ShipmentStatus::values(),
            'returnReasons'    => ReturnReason::labels(),
            'shippingPartners' => ShippingPartner::where('is_active', true)->pluck('name', 'id'),
            'deliverMen'       => Courier::with('user')->active()->get()->mapWithKeys(fn ($d) => [$d->id => $d->user?->name]),
        ]);
    }

    public function update(UpdateShipmentRequest $request, Shipment $shipment)
    {
        if ($request->filled('shipping_partner_id')) {
            $shipment->shipping_partner_id = $request->shipping_partner_id;
            $shipment->save();
        }

        if ($request->filled('deliver_man_id') && (int) $request->deliver_man_id !== (int) $shipment->deliver_man_id) {
            $shipment = $this->shipmentService->assignCourier($shipment, (int) $request->deliver_man_id);
        }

        if ($request->status !== $shipment->status) {
            $shipment = $this->shipmentService->transitionStatus($shipment, $request->status);
        }

        if (in_array($request->status, ['returned', 'refused'], true)) {
            $shipment->update([
                'return_reason' => $request->return_reason,
                'return_note'   => $request->return_note,
            ]);
        }

        toast(__('flash.global.update_title'), 'success');

        return redirect()->route('admin.delivery-orders.show', $shipment);
    }

    public function storeNote(StoreShipmentNoteRequest $request, Shipment $shipment)
    {
        $this->timeline->recordNote($shipment->id, $request->body, auth()->id(), $request->parent_id);
        $shipment->update(['last_status_at' => now()]);

        return $request->ajax() ? response()->json(['success' => true]) : back();
    }

    public function destroy(Shipment $shipment)
    {
        abort_if(Gate::denies('delivery_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $shipment->delete();

        return 1;
    }

    public function massDestroy(MassDestroyShipmentRequest $request)
    {
        Shipment::whereIn('id', $request->ids)->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    protected function dashboardStats(): array
    {
        $base = Shipment::query()->forUser();

        return [
            'today_received'  => (clone $base)->whereDate('handed_to_partner_at', today())->count(),
            'today_delivered' => (clone $base)->whereDate('delivered_at', today())->count(),
            'today_returns'   => (clone $base)->whereDate('returned_at', today())->count(),
            'on_delivery'     => (clone $base)->where('status', ShipmentStatus::OutWithCourier->value)->count(),
            'total_cod'       => (clone $base)->whereDate('created_at', today())->sum('remaining_cod'),
            'total_shipping'  => (clone $base)->whereDate('created_at', today())->sum('shipping_cost'),
        ];
    }
}
