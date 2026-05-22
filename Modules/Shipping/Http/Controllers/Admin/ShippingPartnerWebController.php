<?php

namespace Modules\Shipping\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Shipping\Http\Requests\MassDestroyShippingPartnerRequest;
use Modules\Shipping\Http\Requests\StoreShippingPartnerRequest;
use Modules\Shipping\Http\Requests\UpdateShippingPartnerRequest;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Modules\Shipping\Entities\ShippingPartner;
use Modules\Shipping\Enums\ShipmentStatus;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ShippingPartnerWebController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('shipping_partner_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ShippingPartner::with('user')->select(sprintf('%s.*', (new ShippingPartner)->getTable()));
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                return view('partials.datatablesActions', [
                    'viewGate'      => 'shipping_partner_show',
                    'editGate'      => 'shipping_partner_edit',
                    'deleteGate'    => 'shipping_partner_delete',
                    'crudRoutePart' => 'shipping-partners',
                    'row'           => $row,
                ]);
            });
            $table->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge badge-success">' . __('cruds.shippingPartner.fields.active') . '</span>'
                    : '<span class="badge badge-secondary">' . __('cruds.shippingPartner.fields.inactive') . '</span>';
            });
            $table->addColumn('user_name', fn ($row) => $row->user?->name ?? '');
            $table->addColumn('user_email', fn ($row) => $row->user?->email ?? '');
            $table->rawColumns(['actions', 'placeholder', 'is_active']);

            return $table->make(true);
        }

        return view('shipping::admin.shippingPartners.index');
    }

    public function create()
    {
        abort_if(Gate::denies('shipping_partner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('shipping::admin.shippingPartners.create');
    }

    public function store(StoreShippingPartnerRequest $request)
    {
        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone,
            'password'     => bcrypt($request->password),
            'user_type'    => 'shipping_partner',
            'approved'     => 1,
            'verified'     => 1,
        ]);

        ShippingPartner::create([
            'name'           => $request->name,
            'code'           => $request->code,
            'user_id'        => $user->id,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'is_active'      => $request->boolean('is_active', true),
            'internal_notes' => $request->internal_notes,
        ]);

        toast(__('flash.global.success_title'), 'success');

        return redirect()->route('admin.shipping-partners.index');
    }

    public function edit(ShippingPartner $shippingPartner)
    {
        abort_if(Gate::denies('shipping_partner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $shippingPartner->load('user');

        return view('shipping::admin.shippingPartners.edit', compact('shippingPartner'));
    }

    public function update(UpdateShippingPartnerRequest $request, ShippingPartner $shippingPartner)
    {
        $shippingPartner->update([
            'name'           => $request->name,
            'code'           => $request->code,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'is_active'      => $request->boolean('is_active', true),
            'internal_notes' => $request->internal_notes,
        ]);

        if ($shippingPartner->user_id) {
            $user = User::find($shippingPartner->user_id);
            $user->update([
                'name'         => $request->name,
                'email'        => $request->email,
                'phone_number' => $request->phone,
                'password'     => $request->password ? bcrypt($request->password) : $user->password,
            ]);
        }

        toast(__('flash.global.update_title'), 'success');

        return redirect()->route('admin.shipping-partners.index');
    }

    public function show(ShippingPartner $shippingPartner)
    {
        abort_if(Gate::denies('shipping_partner_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $shippingPartner->load(['user', 'couriers.user']);

        $stats = [
            'today_received'  => $shippingPartner->shipments()->whereDate('handed_to_partner_at', today())->count(),
            'today_delivered' => $shippingPartner->shipments()->whereDate('delivered_at', today())->count(),
            'today_returns'   => $shippingPartner->shipments()->whereDate('returned_at', today())->count(),
            'on_delivery'     => $shippingPartner->shipments()->where('status', ShipmentStatus::OutWithCourier->value)->count(),
            'total_cod'       => $shippingPartner->shipments()->whereDate('created_at', today())->sum('remaining_cod'),
            'total_shipping'  => $shippingPartner->shipments()->whereDate('created_at', today())->sum('shipping_cost'),
        ];

        return view('shipping::admin.shippingPartners.show', compact('shippingPartner', 'stats'));
    }

    public function destroy(ShippingPartner $shippingPartner)
    {
        abort_if(Gate::denies('shipping_partner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $shippingPartner->delete();

        return 1;
    }

    public function massDestroy(MassDestroyShippingPartnerRequest $request)
    {
        ShippingPartner::whereIn('id', $request->ids)->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
