<?php

namespace Modules\Settlement\Http\Controllers\Admin;

use App\Contracts\Shipping\SettlementServiceContract;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Modules\Courier\Entities\Courier;
use Modules\Courier\Repositories\CourierRepository;
use Modules\Settlement\Entities\Settlement;
use Modules\Shipping\Entities\ShippingPartner;
use Modules\Settlement\Enums\SettlementStatus;
use Modules\Settlement\Http\Requests\ConfirmSettlementRequest;
use Modules\Settlement\Http\Requests\OpenSettlementRequest;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SettlementWebController extends Controller
{
    public function __construct(
        protected SettlementServiceContract $settlements,
        protected CourierRepository $couriers,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('delivery_settlement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Settlement::query()
                ->forUser()
                ->with(['courier.user', 'settledBy'])
                ->withCount('lines')
                ->select(sprintf('%s.*', (new Settlement)->getTable()));

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('deliver_man_id')) {
                $query->where('deliver_man_id', $request->deliver_man_id);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('settlement_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('settlement_date', '<=', $request->date_to);
            }

            $table = Datatables::of($query);
            $table->addIndexColumn();
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                return view('partials.datatablesActions', [
                    'viewGate'      => 'delivery_settlement_access',
                    'editGate'      => '',
                    'deleteGate'    => '',
                    'crudRoutePart' => 'settlements',
                    'row'           => $row,
                ]);
            });
            $table->addColumn('courier_name', fn ($row) => $row->courier?->user?->name ?? '-');
            $table->editColumn('status', fn ($row) => '<span class="badge badge-secondary">' . e($row->status_label) . '</span>');
            $table->editColumn('settlement_date', fn ($row) => $row->settlement_date?->format(config('panel.date_format')) ?? '-');
            $table->editColumn('expected_amount', fn ($row) => number_format((float) $row->expected_amount, 2));
            $table->editColumn('collected_amount', fn ($row) => number_format((float) $row->collected_amount, 2));
            $table->editColumn('difference_amount', fn ($row) => number_format((float) $row->difference_amount, 2));
            $table->addColumn('lines_count', fn ($row) => $row->lines_count);
            $table->rawColumns(['actions', 'placeholder', 'status']);

            return $table->make(true);
        }

        return view('settlement::admin.index', [
            'couriers' => $this->couriersPluckForUser(),
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('delivery_settlement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('settlement::admin.create', [
            'couriers' => $this->couriers->activeWithUsers(),
        ]);
    }

    public function preview(Request $request)
    {
        abort_if(Gate::denies('delivery_settlement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'courier_id'            => ['required', 'integer', 'exists:deliver_men,id'],
            'settlement_date'       => ['nullable', 'date'],
            'include_all_unsettled' => ['nullable', 'boolean'],
        ]);

        return response()->json(
            $this->settlements->preview(
                (int) $request->courier_id,
                $request->settlement_date,
                $request->boolean('include_all_unsettled')
            )
        );
    }

    public function store(OpenSettlementRequest $request)
    {
        $settlement = $this->settlements->openSettlement(
            (int) $request->courier_id,
            $request->settlement_date,
            $request->boolean('include_all_unsettled')
        );

        if ($settlement->lines->isEmpty()) {
            toast(__('settlement::messages.no_eligible'), 'warning');

            return redirect()->route('admin.settlements.create');
        }

        toast(__('settlement::messages.opened'), 'success');

        return redirect()->route('admin.settlements.show', $settlement);
    }

    public function show(Settlement $settlement)
    {
        abort_if(Gate::denies('delivery_settlement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settlement->load(['lines.shipment', 'courier.user', 'settledBy']);

        return view('settlement::admin.show', compact('settlement'));
    }

    public function confirm(ConfirmSettlementRequest $request, Settlement $settlement)
    {
        try {
            $this->settlements->confirmSettlement(
                $settlement->id,
                (float) $request->collected_amount,
                $request->notes
            );
        } catch (\InvalidArgumentException $e) {
            toast($e->getMessage(), 'error');

            return back();
        }

        toast(__('settlement::messages.confirmed'), 'success');

        return redirect()->route('admin.settlements.show', $settlement);
    }

    protected function couriersPluckForUser(): \Illuminate\Support\Collection
    {
        $user  = auth()->user();
        $query = Courier::with('user')->active();

        if ($user && $user->user_type === 'shipping_partner') {
            $partnerId = ShippingPartner::where('user_id', $user->id)->value('id');
            $query->where('shipping_partner_id', $partnerId);
        }

        return $query->get()->mapWithKeys(fn ($c) => [$c->id => $c->user?->name]);
    }
}
