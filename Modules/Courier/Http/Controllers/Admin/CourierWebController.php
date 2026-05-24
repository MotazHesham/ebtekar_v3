<?php

namespace Modules\Courier\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Courier\Http\Requests\MassDestroyCourierRequest;
use Modules\Courier\Http\Requests\StoreCourierRequest;
use Modules\Courier\Http\Requests\UpdateCourierRequest;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Modules\Courier\Entities\Courier;
use Modules\Shipping\Entities\ShippingPartner;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CourierWebController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('deliver_man_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Courier::query()->with('user')->select(sprintf('%s.*', (new Courier)->getTable()));

            $user = auth()->user();
            if ($user && $user->user_type === 'shipping_partner') {
                $partnerId = ShippingPartner::where('user_id', $user->id)->value('id');
                $query->where('shipping_partner_id', $partnerId);
            }
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                return view('partials.datatablesActions', [
                    'viewGate'      => 'deliver_man_show',
                    'editGate'      => 'deliver_man_edit',
                    'deleteGate'    => 'deliver_man_delete',
                    'crudRoutePart' => 'deliver-men',
                    'row'           => $row,
                ]);
            });
            $table->editColumn('id', fn ($row) => $row->id);
            $table->addColumn('user_name', fn ($row) => $row->user?->name ?? '');
            $table->addColumn('user_email', fn ($row) => $row->user?->email ?? '');
            $table->addColumn('user_phone_number', fn ($row) => $row->user?->phone_number ?? '');
            $table->addColumn('user_address', fn ($row) => $row->user?->address ?? '');
            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        return view('courier::admin.deliverMen.index');
    }

    public function create()
    {
        abort_if(Gate::denies('deliver_man_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('courier::admin.deliverMen.create', [
            'shippingPartners' => ShippingPartner::where('is_active', true)->pluck('name', 'id'),
        ]);
    }

    public function store(StoreCourierRequest $request)
    {
        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'address'      => $request->address,
            'password'     => bcrypt($request->password),
            'user_type'    => 'courier',
            'approved'     => 1,
            'verified'     => 1,
        ]);

        $courier = Courier::create([
            'user_id'             => $user->id,
            'shipping_partner_id' => $request->shipping_partner_id,
            'status'              => $request->input('status', 'active'),
            'internal_notes'      => $request->internal_notes,
        ]);

        if ($request->file('photo')) {
            $courier->addMedia($request->file('photo'))->toMediaCollection('photo');
        }

        toast(__('flash.global.success_title'), 'success');

        return redirect()->route('admin.deliver-men.index');
    }

    public function edit(Courier $courier)
    {
        abort_if(Gate::denies('deliver_man_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $courier->load('user', 'shippingPartner');

        return view('courier::admin.deliverMen.edit', [
            'deliverMan'       => $courier,
            'shippingPartners' => ShippingPartner::where('is_active', true)->pluck('name', 'id'),
        ]);
    }

    public function update(UpdateCourierRequest $request, Courier $courier)
    {
        $user = User::find($courier->user_id);
        $user->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'address'      => $request->address,
            'password'     => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        $courier->update([
            'shipping_partner_id' => $request->shipping_partner_id,
            'status'              => $request->input('status', 'active'),
            'internal_notes'      => $request->internal_notes,
        ]);

        if ($request->file('photo')) {
            $courier->addMedia($request->file('photo'))->toMediaCollection('photo');
        }

        toast(__('flash.global.update_title'), 'success');

        return redirect()->route('admin.deliver-men.index');
    }

    public function show(Courier $courier)
    {
        abort_if(Gate::denies('deliver_man_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('courier::admin.deliverMen.show', ['deliverMan' => $courier]);
    }

    public function destroy(Courier $courier)
    {
        abort_if(Gate::denies('deliver_man_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $courier->delete();

        return 1;
    }

    public function massDestroy(MassDestroyCourierRequest $request)
    {
        Courier::whereIn('id', $request->ids)->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
