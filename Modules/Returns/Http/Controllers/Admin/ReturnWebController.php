<?php

namespace Modules\Returns\Http\Controllers\Admin;

use App\Contracts\Shipping\ReturnServiceContract;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Modules\Returns\Entities\ReturnCase;
use Modules\Returns\Enums\ReturnReason;
use Modules\Returns\Http\Requests\StoreReturnRequest;
use Modules\Returns\Http\Requests\UploadReturnAttachmentRequest;
use Modules\Shipping\Entities\Shipment;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReturnWebController extends Controller
{
    public function __construct(protected ReturnServiceContract $returns)
    {
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('delivery_return_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ReturnCase::query()
                ->forUser()
                ->with(['shipment', 'courier.user', 'shippingPartner'])
                ->select(sprintf('%s.*', (new ReturnCase)->getTable()));

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('reason')) {
                $query->where('reason', $request->reason);
            }

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                return view('partials.datatablesActions', [
                    'viewGate'      => 'delivery_return_access',
                    'editGate'      => '',
                    'deleteGate'    => '',
                    'crudRoutePart' => 'returns',
                    'row'           => $row,
                ]);
            });
            $table->addColumn('order_num', fn ($row) => $row->shipment?->order_num ?? '-');
            $table->addColumn('courier_name', fn ($row) => $row->courier?->user?->name ?? '-');
            $table->editColumn('reason', fn ($row) => e($row->reason_label));
            $table->editColumn('status', fn ($row) => '<span class="badge badge-warning">' . e($row->status_label) . '</span>');
            $table->editColumn('created_at', fn ($row) => $row->created_at
                ? Carbon::parse($row->created_at)->format(config('panel.date_format') . ' ' . config('panel.time_format'))
                : '-');
            $table->rawColumns(['actions', 'placeholder', 'status']);

            return $table->make(true);
        }

        return view('returns::admin.index');
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('delivery_return_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shipment = $request->filled('shipment_id')
            ? Shipment::find($request->shipment_id)
            : null;

        return view('returns::admin.create', [
            'shipment'      => $shipment,
            'returnReasons' => ReturnReason::labels(),
        ]);
    }

    public function store(StoreReturnRequest $request)
    {
        try {
            $case = $this->returns->registerReturn(
                (int) $request->shipment_id,
                $request->reason,
                $request->note,
                $request->input('shipment_status', ReturnReason::shipmentStatusFor($request->reason))
            );
        } catch (\InvalidArgumentException $e) {
            toast($e->getMessage(), 'error');

            return back()->withInput();
        }

        toast(__('returns::messages.registered'), 'success');

        return redirect()->route('admin.returns.show', $case);
    }

    public function show(ReturnCase $return)
    {
        abort_if(Gate::denies('delivery_return_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $return->load(['shipment', 'courier.user', 'shippingPartner', 'createdBy', 'media']);

        return view('returns::admin.show', [
            'returnCase' => $return,
        ]);
    }

    public function uploadAttachment(UploadReturnAttachmentRequest $request, ReturnCase $return)
    {
        foreach ($request->file('attachments', []) as $file) {
            $return->addMedia($file)->toMediaCollection('return_proofs');
        }

        toast(__('returns::messages.attachment_uploaded'), 'success');

        return back();
    }

    public function markWarehouse(ReturnCase $return)
    {
        abort_if(Gate::denies('delivery_return_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $this->returns->markWarehouseReceived($return->id);
        } catch (\InvalidArgumentException $e) {
            toast($e->getMessage(), 'error');

            return back();
        }

        toast(__('returns::messages.warehouse_marked'), 'success');

        return back();
    }

    public function close(ReturnCase $return)
    {
        abort_if(Gate::denies('delivery_return_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $this->returns->closeReturn($return->id);
        } catch (\InvalidArgumentException $e) {
            toast($e->getMessage(), 'error');

            return back();
        }

        toast(__('returns::messages.closed'), 'success');

        return back();
    }
}
