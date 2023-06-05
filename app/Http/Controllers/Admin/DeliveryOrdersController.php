<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDeliveryOrderRequest;
use App\Http\Requests\StoreDeliveryOrderRequest;
use App\Http\Requests\UpdateDeliveryOrderRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeliveryOrdersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('delivery_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.deliveryOrders.index');
    }

    public function create()
    {
        abort_if(Gate::denies('delivery_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.deliveryOrders.create');
    }

    public function store(StoreDeliveryOrderRequest $request)
    {
        $deliveryOrder = DeliveryOrder::create($request->all());

        return redirect()->route('admin.delivery-orders.index');
    }

    public function edit(DeliveryOrder $deliveryOrder)
    {
        abort_if(Gate::denies('delivery_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.deliveryOrders.edit', compact('deliveryOrder'));
    }

    public function update(UpdateDeliveryOrderRequest $request, DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->update($request->all());

        return redirect()->route('admin.delivery-orders.index');
    }

    public function show(DeliveryOrder $deliveryOrder)
    {
        abort_if(Gate::denies('delivery_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.deliveryOrders.show', compact('deliveryOrder'));
    }

    public function destroy(DeliveryOrder $deliveryOrder)
    {
        abort_if(Gate::denies('delivery_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyDeliveryOrderRequest $request)
    {
        $deliveryOrders = DeliveryOrder::find(request('ids'));

        foreach ($deliveryOrders as $deliveryOrder) {
            $deliveryOrder->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
