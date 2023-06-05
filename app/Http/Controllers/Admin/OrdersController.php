<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Country;
use App\Models\Order;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Order::with(['user', 'shipping_country', 'designer', 'preparer', 'manufacturer', 'shipment', 'delivery_man'])->select(sprintf('%s.*', (new Order)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'order_show';
                $editGate      = 'order_edit';
                $deleteGate    = 'order_delete';
                $crudRoutePart = 'orders';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('order_type', function ($row) {
                return $row->order_type ? Order::ORDER_TYPE_SELECT[$row->order_type] : '';
            });
            $table->editColumn('order_num', function ($row) {
                return $row->order_num ? $row->order_num : '';
            });
            $table->editColumn('client_name', function ($row) {
                return $row->client_name ? $row->client_name : '';
            });
            $table->editColumn('phone_number', function ($row) {
                return $row->phone_number ? $row->phone_number : '';
            });
            $table->editColumn('phone_number_2', function ($row) {
                return $row->phone_number_2 ? $row->phone_number_2 : '';
            });
            $table->editColumn('shipping_address', function ($row) {
                return $row->shipping_address ? $row->shipping_address : '';
            });
            $table->editColumn('shipping_country_name', function ($row) {
                return $row->shipping_country_name ? $row->shipping_country_name : '';
            });
            $table->editColumn('shipping_country_cost', function ($row) {
                return $row->shipping_country_cost ? $row->shipping_country_cost : '';
            });
            $table->editColumn('shipping_cost_by_seller', function ($row) {
                return $row->shipping_cost_by_seller ? $row->shipping_cost_by_seller : '';
            });
            $table->editColumn('free_shipping', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->free_shipping ? 'checked' : null) . '>';
            });
            $table->editColumn('free_shipping_reason', function ($row) {
                return $row->free_shipping_reason ? $row->free_shipping_reason : '';
            });
            $table->editColumn('printing_times', function ($row) {
                return $row->printing_times ? $row->printing_times : '';
            });
            $table->editColumn('completed', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->completed ? 'checked' : null) . '>';
            });
            $table->editColumn('calling', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->calling ? 'checked' : null) . '>';
            });
            $table->editColumn('supplied', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->supplied ? 'checked' : null) . '>';
            });

            $table->editColumn('playlist_status', function ($row) {
                return $row->playlist_status ? Order::PLAYLIST_STATUS_SELECT[$row->playlist_status] : '';
            });
            $table->editColumn('payment_status', function ($row) {
                return $row->payment_status ? Order::PAYMENT_STATUS_SELECT[$row->payment_status] : '';
            });
            $table->editColumn('delivery_status', function ($row) {
                return $row->delivery_status ? Order::DELIVERY_STATUS_SELECT[$row->delivery_status] : '';
            });
            $table->editColumn('payment_type', function ($row) {
                return $row->payment_type ? Order::PAYMENT_TYPE_SELECT[$row->payment_type] : '';
            });
            $table->editColumn('commission_status', function ($row) {
                return $row->commission_status ? Order::COMMISSION_STATUS_SELECT[$row->commission_status] : '';
            });
            $table->editColumn('deposit_type', function ($row) {
                return $row->deposit_type ? Order::DEPOSIT_TYPE_SELECT[$row->deposit_type] : '';
            });
            $table->editColumn('deposit_amount', function ($row) {
                return $row->deposit_amount ? $row->deposit_amount : '';
            });
            $table->editColumn('total_cost_by_seller', function ($row) {
                return $row->total_cost_by_seller ? $row->total_cost_by_seller : '';
            });
            $table->editColumn('total_cost', function ($row) {
                return $row->total_cost ? $row->total_cost : '';
            });
            $table->editColumn('commission', function ($row) {
                return $row->commission ? $row->commission : '';
            });
            $table->editColumn('extra_commission', function ($row) {
                return $row->extra_commission ? $row->extra_commission : '';
            });
            $table->editColumn('discount', function ($row) {
                return $row->discount ? $row->discount : '';
            });
            $table->editColumn('discount_code', function ($row) {
                return $row->discount_code ? $row->discount_code : '';
            });
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });
            $table->editColumn('cancel_reason', function ($row) {
                return $row->cancel_reason ? $row->cancel_reason : '';
            });
            $table->editColumn('delay_reason', function ($row) {
                return $row->delay_reason ? $row->delay_reason : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->addColumn('shipping_country_name', function ($row) {
                return $row->shipping_country ? $row->shipping_country->name : '';
            });

            $table->addColumn('designer_name', function ($row) {
                return $row->designer ? $row->designer->name : '';
            });

            $table->addColumn('preparer_name', function ($row) {
                return $row->preparer ? $row->preparer->name : '';
            });

            $table->addColumn('manufacturer_name', function ($row) {
                return $row->manufacturer ? $row->manufacturer->name : '';
            });

            $table->addColumn('shipment_name', function ($row) {
                return $row->shipment ? $row->shipment->name : '';
            });

            $table->addColumn('delivery_man_name', function ($row) {
                return $row->delivery_man ? $row->delivery_man->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'free_shipping', 'completed', 'calling', 'supplied', 'user', 'shipping_country', 'designer', 'preparer', 'manufacturer', 'shipment', 'delivery_man']);

            return $table->make(true);
        }

        return view('admin.orders.index');
    }

    public function create()
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shipping_countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preparers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $delivery_men = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.orders.create', compact('delivery_men', 'preparers', 'shipping_countries', 'users'));
    }

    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->all());

        return redirect()->route('admin.orders.index');
    }

    public function edit(Order $order)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shipping_countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preparers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $delivery_men = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $order->load('user', 'shipping_country', 'designer', 'preparer', 'manufacturer', 'shipment', 'delivery_man');

        return view('admin.orders.edit', compact('delivery_men', 'order', 'preparers', 'shipping_countries', 'users'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->all());

        return redirect()->route('admin.orders.index');
    }

    public function show(Order $order)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load('user', 'shipping_country', 'designer', 'preparer', 'manufacturer', 'shipment', 'delivery_man');

        return view('admin.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->delete();

        return back();
    }

    public function massDestroy(MassDestroyOrderRequest $request)
    {
        $orders = Order::find(request('ids'));

        foreach ($orders as $order) {
            $order->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
