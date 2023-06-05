@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.order.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.id') }}
                        </th>
                        <td>
                            {{ $order->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.paymob_orderid') }}
                        </th>
                        <td>
                            {{ $order->paymob_orderid }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.order_type') }}
                        </th>
                        <td>
                            {{ App\Models\Order::ORDER_TYPE_SELECT[$order->order_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.order_num') }}
                        </th>
                        <td>
                            {{ $order->order_num }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.client_name') }}
                        </th>
                        <td>
                            {{ $order->client_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.phone_number') }}
                        </th>
                        <td>
                            {{ $order->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.phone_number_2') }}
                        </th>
                        <td>
                            {{ $order->phone_number_2 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.shipping_address') }}
                        </th>
                        <td>
                            {{ $order->shipping_address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.shipping_country_name') }}
                        </th>
                        <td>
                            {{ $order->shipping_country_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.shipping_country_cost') }}
                        </th>
                        <td>
                            {{ $order->shipping_country_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.shipping_cost_by_seller') }}
                        </th>
                        <td>
                            {{ $order->shipping_cost_by_seller }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.free_shipping') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $order->free_shipping ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.free_shipping_reason') }}
                        </th>
                        <td>
                            {{ $order->free_shipping_reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.printing_times') }}
                        </th>
                        <td>
                            {{ $order->printing_times }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.completed') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $order->completed ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.calling') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $order->calling ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.supplied') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $order->supplied ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.done_time') }}
                        </th>
                        <td>
                            {{ $order->done_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.send_to_delivery_date') }}
                        </th>
                        <td>
                            {{ $order->send_to_delivery_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.send_to_playlist_date') }}
                        </th>
                        <td>
                            {{ $order->send_to_playlist_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.date_of_receiving_order') }}
                        </th>
                        <td>
                            {{ $order->date_of_receiving_order }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.excepected_deliverd_date') }}
                        </th>
                        <td>
                            {{ $order->excepected_deliverd_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.playlist_status') }}
                        </th>
                        <td>
                            {{ App\Models\Order::PLAYLIST_STATUS_SELECT[$order->playlist_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.payment_status') }}
                        </th>
                        <td>
                            {{ App\Models\Order::PAYMENT_STATUS_SELECT[$order->payment_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.delivery_status') }}
                        </th>
                        <td>
                            {{ App\Models\Order::DELIVERY_STATUS_SELECT[$order->delivery_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.payment_type') }}
                        </th>
                        <td>
                            {{ App\Models\Order::PAYMENT_TYPE_SELECT[$order->payment_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.commission_status') }}
                        </th>
                        <td>
                            {{ App\Models\Order::COMMISSION_STATUS_SELECT[$order->commission_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.deposit_type') }}
                        </th>
                        <td>
                            {{ App\Models\Order::DEPOSIT_TYPE_SELECT[$order->deposit_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.deposit_amount') }}
                        </th>
                        <td>
                            {{ $order->deposit_amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.total_cost_by_seller') }}
                        </th>
                        <td>
                            {{ $order->total_cost_by_seller }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.total_cost') }}
                        </th>
                        <td>
                            {{ $order->total_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.commission') }}
                        </th>
                        <td>
                            {{ $order->commission }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.extra_commission') }}
                        </th>
                        <td>
                            {{ $order->extra_commission }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.discount') }}
                        </th>
                        <td>
                            {{ $order->discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.discount_code') }}
                        </th>
                        <td>
                            {{ $order->discount_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.note') }}
                        </th>
                        <td>
                            {{ $order->note }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.cancel_reason') }}
                        </th>
                        <td>
                            {{ $order->cancel_reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.delay_reason') }}
                        </th>
                        <td>
                            {{ $order->delay_reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.user') }}
                        </th>
                        <td>
                            {{ $order->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.shipping_country') }}
                        </th>
                        <td>
                            {{ $order->shipping_country->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.designer') }}
                        </th>
                        <td>
                            {{ $order->designer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.preparer') }}
                        </th>
                        <td>
                            {{ $order->preparer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.manufacturer') }}
                        </th>
                        <td>
                            {{ $order->manufacturer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.shipment') }}
                        </th>
                        <td>
                            {{ $order->shipment->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.delivery_man') }}
                        </th>
                        <td>
                            {{ $order->delivery_man->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection