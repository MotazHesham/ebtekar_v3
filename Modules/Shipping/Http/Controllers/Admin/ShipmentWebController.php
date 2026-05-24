<?php

namespace Modules\Shipping\Http\Controllers\Admin;

use App\Contracts\Shipping\DispatchAssignmentContract;
use App\Contracts\Shipping\ReturnServiceContract;
use App\Contracts\Shipping\ShipmentServiceContract;
use App\Contracts\Shipping\TimelineRecorderContract;
use App\Exports\ShipmentsExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Courier\Entities\Courier;
use Modules\Courier\Repositories\CourierRepository;
use Modules\Returns\Enums\ReturnReason;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Entities\ShippingPartner;
use Modules\Shipping\Enums\ShipmentStatus;
use Modules\Shipping\Http\Requests\MassDestroyShipmentRequest;
use Modules\Shipping\Http\Requests\QuickShipmentStatusRequest;
use Modules\Shipping\Http\Requests\StoreShipmentNoteRequest;
use Modules\Shipping\Http\Requests\UpdateShipmentRequest;
use Modules\Shipping\Repositories\ShippingPartnerRepository;
use Modules\Shipping\Services\ShipmentStatusOperationsService;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ShipmentWebController extends Controller
{
    public function __construct(
        protected ShipmentServiceContract $shipmentService,
        protected ShipmentStatusOperationsService $statusOps,
        protected DispatchAssignmentContract $dispatchAssignment,
        protected ReturnServiceContract $returnService,
        protected TimelineRecorderContract $timeline,
        protected CourierRepository $couriers,
        protected ShippingPartnerRepository $partners,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('delivery_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = $this->filteredQuery($request);

            $table = Datatables::of($query);
            $table->addIndexColumn();
            $table->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="shipment-row-select" value="' . $row->id . '" data-cod="' . e((float) $row->remaining_cod) . '">';
            });
            $table->addColumn('quick_actions', fn ($row) => $this->quickActionsHtml($row));
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                return view('partials.datatablesActions', [
                    'viewGate'      => 'delivery_order_show',
                    'editGate'      => '',
                    'deleteGate'    => 'delivery_order_delete',
                    'crudRoutePart' => 'delivery-orders',
                    'row'           => $row,
                ]);
            });
            $table->editColumn('status', fn ($row) => '<span class="badge badge-info">' . e($row->status_label) . '</span>');
            $table->addColumn('partner_name', fn ($row) => $row->shippingPartner?->name ?? '-');
            $table->addColumn('courier_name', fn ($row) => $row->courier?->user?->name ?? '-');
            $table->addColumn('full_address', fn ($row) => e($row->full_address ?? '-'));
            $table->editColumn('governorate', fn ($row) => e($row->governorate ?? '-'));
            $table->editColumn('region', fn ($row) => e($row->region ?? '-'));
            $table->editColumn('last_status_at', function ($row) {
                if (! $row->getRawOriginal('last_status_at')) {
                    return '-';
                }

                return Carbon::parse($row->getRawOriginal('last_status_at'))
                    ->format(config('panel.date_format') . ' ' . config('panel.time_format'));
            });
            $table->editColumn('pending_since', fn ($row) => $row->pending_since ?? '-');
            $table->editColumn('remaining_cod', fn ($row) => number_format((float) $row->remaining_cod, 2));
            $table->rawColumns(['actions', 'checkbox', 'quick_actions', 'status']);

            return $table->make(true);
        }

        $user = auth()->user();

        return view('shipping::admin.deliveryOrders.index', [
            'shippingPartners' => $this->partners->activePluck(),
            'deliverMen'       => $this->couriersPluckForUser(),
            'dashboardStats'   => $this->dashboardStats($request),
            'showPartnerFilter' => ! $user || ! in_array($user->user_type, ['shipping_partner', 'courier', 'delivery_man'], true),
            'showCourierFilter' => ! $user || ! in_array($user->user_type, ['courier', 'delivery_man'], true),
            'canMarkDelivered'  => Gate::allows('delivery_order_mark_delivered') || Gate::allows('delivery_order_edit'),
            'canExport'         => Gate::allows('delivery_export') || $user?->is_admin,
        ]);
    }

    public function stats(Request $request)
    {
        abort_if(Gate::denies('delivery_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return response()->json($this->dashboardStats($request));
    }

    public function quickStatus(QuickShipmentStatusRequest $request)
    {
        $shipment = Shipment::query()->forUser()->findOrFail($request->integer('shipment_id'));

        try {
            match ($request->action) {
                'delivered' => $this->statusOps->markDelivered($shipment, auth()->id(), $request->note),
                'returned'  => $this->statusOps->registerReturnFromCourier(
                    $shipment,
                    $request->return_reason ?: ReturnReason::Other->value,
                    $request->note,
                    auth()->id()
                ),
                'revert_handoff' => $this->statusOps->revertHandoff($shipment, auth()->id(), $request->note),
                'cancel_delivered' => $this->statusOps->cancelDelivered($shipment, auth()->id(), $request->note),
                'cancel_return' => $this->statusOps->cancelReturn($shipment, auth()->id(), $request->note),
            };
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'message' => __('delivery.messages.status_updated'),
        ]);
    }

    public function export(Request $request)
    {
        abort_unless(Gate::allows('delivery_export') || auth()->user()?->is_admin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = $this->filteredQuery($request);

        if ($request->filled('ids')) {
            $ids = array_map('intval', (array) $request->input('ids'));
            $query->whereIn('id', $ids);
        }

        $shipments = $query->with(['shippingPartner', 'courier.user', 'orderable'])->get();

        return Excel::download(
            new ShipmentsExport($shipments),
            'shipments-' . now()->format('Y-m-d-His') . '.xlsx'
        );
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

        $partnerId = $shipment->shipping_partner_id;

        return view('shipping::admin.deliveryOrders.show', [
            'deliveryOrder'    => $shipment,
            'statuses'         => ShipmentStatus::values(),
            'returnReasons'    => ReturnReason::labels(),
            'shippingPartners' => ShippingPartner::where('is_active', true)->pluck('name', 'id'),
            'deliverMen'       => $this->couriersPluckForPartner($partnerId),
            'canOverride'      => Gate::allows('delivery_order_status_override'),
            'canRevertHandoff' => Gate::allows('delivery_order_revert_handoff'),
            'canCancelDelivered' => Gate::allows('delivery_order_cancel_delivered'),
            'canCancelReturn'    => Gate::allows('delivery_order_cancel_return'),
        ]);
    }

    public function update(UpdateShipmentRequest $request, Shipment $shipment)
    {
        $canOverride = Gate::allows('delivery_order_status_override');

        if ($request->filled('shipping_partner_id')) {
            $shipment->shipping_partner_id = $request->shipping_partner_id;
            $shipment->save();
        }

        if ($request->filled('deliver_man_id') && (int) $request->deliver_man_id !== (int) $shipment->deliver_man_id) {
            $courier = Courier::query()->find($request->deliver_man_id);
            if ($courier && $shipment->shipping_partner_id && (int) $courier->shipping_partner_id !== (int) $shipment->shipping_partner_id) {
                toast(__('delivery.errors.courier_partner_mismatch'), 'error');

                return redirect()->route('admin.delivery-orders.show', $shipment);
            }

            $result = $this->dispatchAssignment->assignOne($shipment, (int) $request->deliver_man_id);
            if (! $result->ok) {
                toast($result->message, 'error');

                return redirect()->route('admin.delivery-orders.show', $shipment);
            }
            $shipment = $result->shipment ?? $shipment->fresh();
        }

        if ($request->status !== $shipment->status) {
            if (! $canOverride && ! Gate::allows('delivery_order_edit')) {
                abort(403);
            }

            if ($request->status === ShipmentStatus::Delivered->value) {
                $this->statusOps->markDelivered($shipment, auth()->id());
            } elseif (in_array($request->status, ['returned', 'refused'], true)) {
                try {
                    $this->returnService->registerReturn(
                        $shipment,
                        $request->return_reason ?: 'other',
                        $request->return_note,
                        $request->status
                    );
                } catch (\InvalidArgumentException $e) {
                    toast($e->getMessage(), 'error');

                    return redirect()->route('admin.delivery-orders.show', $shipment);
                }
            } else {
                $shipment = $this->shipmentService->transitionStatus($shipment, $request->status);
            }
        } elseif (in_array($request->status, ['returned', 'refused'], true)) {
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

    protected function filteredQuery(Request $request)
    {
        $query = Shipment::query()
            ->forUser()
            ->with(['shippingPartner', 'courier.user', 'orderable'])
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

        return $query;
    }

    protected function dashboardStats(?Request $request = null): array
    {
        $base = Shipment::query()->forUser();

        if ($request?->filled('shipping_partner_id')) {
            $base->where('shipping_partner_id', $request->integer('shipping_partner_id'));
        }

        $outStatuses = [ShipmentStatus::OutWithCourier->value, ShipmentStatus::Retry->value, ShipmentStatus::Postponed->value, ShipmentStatus::CustomerUnavailable->value];

        return [
            'today_received'      => (clone $base)->whereDate('received_by_partner_at', today())->count(),
            'today_delivered'     => (clone $base)->whereDate('delivered_at', today())->count(),
            'today_returns'       => (clone $base)->whereIn('status', [ShipmentStatus::Returned->value, ShipmentStatus::Refused->value])->whereDate('returned_at', today())->count(),
            'on_delivery'         => (clone $base)->whereIn('status', $outStatuses)->count(),
            'total_cod_collect'   => number_format((float) (clone $base)->whereIn('status', $outStatuses)->sum('remaining_cod'), 2),
            'total_cod_collected' => number_format((float) (clone $base)->where('status', ShipmentStatus::Delivered->value)->sum('remaining_cod'), 2),
            'total_returns_amount'=> number_format((float) (clone $base)->whereIn('status', [ShipmentStatus::Returned->value, ShipmentStatus::Refused->value])->sum('remaining_cod'), 2),
            'total_shipping_cost' => number_format((float) (clone $base)->sum('shipping_cost'), 2),
        ];
    }

    protected function couriersPluckForUser(): \Illuminate\Support\Collection
    {
        $user = auth()->user();
        $query = Courier::with('user')->active();

        if ($user && $user->user_type === 'shipping_partner') {
            $partnerId = ShippingPartner::where('user_id', $user->id)->value('id');
            $query->where('shipping_partner_id', $partnerId);
        }

        return $query->get()->mapWithKeys(fn ($d) => [$d->id => $d->user?->name]);
    }

    protected function couriersPluckForPartner(?int $partnerId): \Illuminate\Support\Collection
    {
        $query = Courier::with('user')->active();
        if ($partnerId) {
            $query->where('shipping_partner_id', $partnerId);
        }

        return $query->get()->mapWithKeys(fn ($d) => [$d->id => $d->user?->name]);
    }

    protected function quickActionsHtml(Shipment $row): string
    {
        if (! Gate::allows('delivery_order_mark_delivered') && ! Gate::allows('delivery_order_edit')) {
            return '';
        }

        $html = '';

        if (! in_array($row->status, [ShipmentStatus::Delivered->value, ShipmentStatus::Returned->value, ShipmentStatus::Refused->value, ShipmentStatus::Closed->value], true)) {
            $html .= '<button type="button" class="btn btn-xs btn-success btn-quick-delivered" data-id="' . $row->id . '">' . e(__('delivery.actions.mark_delivered')) . '</button> ';
        }

        if (Gate::allows('delivery_order_revert_handoff') && $row->status === ShipmentStatus::HandedToPartner->value) {
            $html .= '<button type="button" class="btn btn-xs btn-warning btn-quick-revert" data-id="' . $row->id . '">' . e(__('delivery.actions.revert_handoff')) . '</button>';
        }

        return $html;
    }
}
