@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-light" href="{{ route('admin.orders.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>

<div class="row">
    <div class="col-md-8"> 
        <div class="row">
            <div class="col-md-6"> 
                <div class="card">
                    <div class="card-header">
                        معلومات المشتري
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tbody> 
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
                                        <span class="badge badge-warning">{{ $order->shipping_country ? $order->shipping_country->name : '' }}</span>
                                        {{ $order->shipping_address }}
                                    </td>
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="card">
                    <div class="card-header">
                        معلومات البائع
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                @php
                                    $seller = \App\Models\Seller::where('user_id',$order->user_id)->first();
                                @endphp
                                <tr>
                                    <th>
                                        {{ trans('cruds.seller.fields.seller_code') }}
                                    </th>
                                    <td>
                                        {{ $seller ? $seller->seller_code : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.email') }}
                                    </th>
                                    <td>
                                        {{ $order->user ? $order->user->email : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.seller.fields.social_name') }}
                                    </th>
                                    <td>
                                        {{ $seller ? $seller->social_name : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.seller.fields.social_link') }}
                                    </th>
                                    <td>
                                        {{ $seller ? $seller->social_link : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.order.fields.commission') }}
                                    </th>
                                    <td>
                                        {{ dashboard_currency($order->commission + $order->extra_commission) }}
                                    </td>
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4"> 
        @include('partials.delivery_man',[
                'site_settings' => $site_settings,
                'row' => $order, 
                'crudRoutePart' => 'admin.orders.',
                'response' => $response,
            ])
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                @if($order->orderDetails)
                    <table  class="table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm">
                        <thead>
                            <th>{{ trans('cruds.order.extra.id') }}</th>
                            <th>{{ trans('cruds.order.extra.photo') }}</th>
                            <th>{{ trans('cruds.order.extra.product') }}</th>
                            <th>{{ trans('cruds.order.extra.total_cost') }}</th>
                            <th>{{ trans('cruds.order.extra.commission') }}</th>
                            <th>{{ trans('cruds.order.extra.extra_commission') }}</th>
                            <th></th>
                        </thead>
                        <tbody>
                                @foreach($order->orderDetails as $orderDetail)
                                    <form action="{{ route('admin.orders.update_order_detail') }}" method="POST">
                                        @csrf 
                                        <input type="hidden" name="id" value="{{$orderDetail->id}}">
                                        <tr>
                                            <td>{{ $orderDetail->id }}</td> 
                                            <td>{{ $orderDetail->product ?  $orderDetail->product->photo : '' }}</td>
                                            <td>{{ $orderDetail->product ?  $orderDetail->product->name : ''}}</td>
                                            <td>
                                                <span class="badge badge-dark">{{ trans('cruds.order.extra.quantity') }} {{ $orderDetail->quantity }}</span>
                                                <span class="badge badge-dark">{{ trans('cruds.order.extra.price') }} {{ dashboard_currency($orderDetail->price) }}</span>
                                                <br>
                                                <span class="badge badge-success">{{ trans('cruds.order.extra.total_cost') }} {{ dashboard_currency($orderDetail->total_cost) }}</span>
        
                                            </td>
                                            <td>{{ dashboard_currency($orderDetail->commission) }}</td>
                                            <td><input style="min-width: 70px" type="number" class="form-control" name="extra_commission" value="{{ $orderDetail->extra_commission }}" required></td>
                                            <td>
                                                
                                                <a class="btn btn-primary" href="#" onclick="show_details('{{$orderDetail->id}}')">
                                                    {{ trans('global.view') }} 
                                                </a>
                                                <button class="btn btn-info" type="submit">
                                                    {{ trans('global.update') }} 
                                                </button>
                                                <?php $route = route('admin.orders.destroy_product', $orderDetail->id); ?>
                                                <a class="btn btn-danger" href="#"  onclick="deleteConfirmation('{{$route}}')">
                                                    {{ trans('global.delete') }} 
                                                </a>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-5"> 
                            <table  class="table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>{{ trans('cruds.order.extra.sub_total') }} :</strong>
                                        </td>
                                        <td>
                                            + {{ dashboard_currency($order->total_cost) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>{{ trans('cruds.order.fields.extra_commission') }} :</strong>
                                        </td>
                                        <td>
                                            + {{ dashboard_currency($order->extra_commission) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>{{ trans('cruds.order.fields.deposit_amount') }} :</strong>
                                        </td>
                                        <td>
                                            - {{ dashboard_currency($order->deposit_amount) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>{{ trans('cruds.order.extra.shipping_country_cost') }} :</strong>
                                        </td>
                                        <td>
                                            + {{ dashboard_currency($order->shipping_country_cost) }}
                                        </td>
                                    </tr>
                                    <tr style="background: #34828285">
                                        <td>
                                            <strong>{{ trans('cruds.order.fields.total_cost') }} :</strong>
                                        </td>
                                        <td class="text-bold h4">
                                            = {{ dashboard_currency($order->calc_total_for_client()) }}
                                            @if($order->discount_code != null)
                                                <br>
                                                <span class="badge badge-purple">
                                                    كود الخصم {{ $order->discount_code }}
                                                    /
                                                    {{ dashboard_currency($order->calc_discount()) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                معلومات الطلب
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tbody> 
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.id') }}
                            </th>
                            <td>
                                {{ $order->id ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.order_num') }}
                            </th>
                            <td>
                                {{ $order->order_num ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.delivery_status') }}
                            </th>
                            <td>
                                {{ $order->delivery_status ? trans('global.delivery_status.status.' . $order->delivery_status) : '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.deposit_amount') }}
                            </th>
                            <td>
                                {{ $order->deposit_amount ? dashboard_currency($order->deposit_amount) : '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.deposit_type') }}
                            </th>
                            <td>
                                {{ $order->deposit_type ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.created_at') }}
                            </th>
                            <td>
                                {{ $order->created_at ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th> 
                                {{ trans('cruds.order.fields.date_of_receiving_order') }}
                            </th>
                            <td>
                                {{ $order->date_of_recieving_money ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.excepected_deliverd_date') }}
                            </th>
                            <td>
                                {{ $order->excepected_deliverd_date ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.total_cost_by_seller') }}
                            </th>
                            <td>
                                {{ $order->total_cost_by_seller ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.free_shipping') }}
                            </th>
                            <td>
                                {{ $order->free_shipping ? 'Yes' : 'No' }}
                                @if($order->free_shipping_reason)
                                    {{ $order->free_shipping_reason ?? '' }}
                                @endif
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.shipping_cost_by_seller') }}
                            </th>
                            <td>
                                {{ $order->shipping_cost_by_seller ?? '' }}
                            </td> 
                        </tr> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <a class="btn btn-light" href="{{ route('admin.orders.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>


@endsection

@section('scripts')
@parent 
    <script>
        function show_details(id){ 
            $.post('{{ route('admin.orders.show_order_detail') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);
            });
        }
    </script>
    
@endsection