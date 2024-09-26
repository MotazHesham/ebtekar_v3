@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-light" href="{{ route('admin.orders.index') }}">
        {{ __('global.back_to_list') }}
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
                                        {{ __('cruds.order.fields.client_name') }}
                                    </th>
                                    <td>
                                        {{ $order->client_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.order.fields.phone_number') }}
                                    </th>
                                    <td>
                                        {{ $order->phone_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.order.fields.phone_number_2') }}
                                    </th>
                                    <td>
                                        {{ $order->phone_number_2 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.order.fields.shipping_address') }}
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
                                        {{ __('cruds.seller.fields.seller_code') }}
                                    </th>
                                    <td>
                                        {{ $seller ? $seller->seller_code : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.user.fields.email') }}
                                    </th>
                                    <td>
                                        {{ $order->user ? $order->user->email : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.seller.fields.social_name') }}
                                    </th>
                                    <td>
                                        {{ $seller ? $seller->social_name : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.seller.fields.social_link') }}
                                    </th>
                                    <td>
                                        {{ $seller ? $seller->social_link : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.order.fields.commission') }}
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

                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-12">
                            <button class="btn btn-success" onclick="add_order_detail('{{$order->id}}')">
                                {{ __('global.add') }} {{ __('cruds.product.title_singular') }}
                            </button>
                        </div>
                    </div> 

                    <table  class="table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm">
                        <thead>
                            <th>{{ __('cruds.order.extra.id') }}</th>
                            <th>{{ __('cruds.order.extra.product') }}</th>
                            <th>{{ __('cruds.order.extra.variation') }}</th>
                            <th>{{ __('cruds.order.extra.total_cost') }}</th>
                            <th>{{ __('cruds.order.extra.commission') }}</th> 
                            <th></th>
                        </thead>
                        <tbody>
                                @foreach($order->orderDetails as $orderDetail)
                                    <tr>
                                        <td>{{ $orderDetail->id }}</td> 
                                        <td>
                                            @foreach($orderDetail->product->photos as $media)
                                                <img src="{{ $media->getUrl('thumb')}}" alt="">
                                            @endforeach
                                            <br>
                                            <a style="color: black" target="_blanc" href="{{ route('admin.products.show',$orderDetail->product->id ?? 1) }}"> {{ $orderDetail->product->name ?? 'Deleted' }}</a>
                                        </td>
                                        <td>{{ $orderDetail->variation ?? '' }}</td>
                                        <td>
                                            <span class="badge badge-dark">{{ __('cruds.order.extra.quantity') }} {{ $orderDetail->quantity }}</span>
                                            <span class="badge badge-dark">{{ __('cruds.order.extra.price') }} {{ $orderDetail->calc_price($order->exchange_rate) }} {{ $order->symbol }}</span>
                                            <br>
                                            <span class="badge badge-success">{{ __('cruds.order.extra.total_cost') }} {{ $orderDetail->total_cost($order->exchange_rate) }} {{ $order->symbol }}</span>
    
                                        </td>
                                        <td>
                                            {{ dashboard_currency($orderDetail->commission) }}
                                            @if($orderDetail->extra_commission)
                                                <br>
                                                <span class="badge badge-success">
                                                    {{ __('cruds.order.extra.extra_commission') }}
                                                    {{ dashboard_currency($orderDetail->extra_commission) }}
                                                </span>
                                            @endif
                                        </td> 
                                        <td> 
                                            <a class="btn btn-primary" href="#" onclick="show_details('{{$orderDetail->id}}')">
                                                {{ __('global.view') }} 
                                            </a>
                                            <button class="btn btn-info" onclick="edit_order_detail('{{$orderDetail->id}}')">
                                                {{ __('global.edit') }} 
                                            </button>
                                            <?php $route = route('admin.orders.destroy_product', $orderDetail->id); ?>
                                            <a class="btn btn-danger" href="#"  onclick="deleteConfirmation('{{$route}}')">
                                                {{ __('global.delete') }} 
                                            </a>
                                        </td>
                                    </tr> 
                                @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-5"> 
                            <table  class="table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>{{ __('cruds.order.extra.sub_total') }} :</strong>
                                        </td>
                                        <td>
                                            + {{ exchange_rate($order->total_cost,$order->exchange_rate) }} {{ $order->symbol }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>{{ __('cruds.order.fields.extra_commission') }} :</strong>
                                        </td>
                                        <td>
                                            + {{ exchange_rate($order->extra_commission,$order->exchange_rate) }} {{ $order->symbol }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>{{ __('cruds.order.fields.deposit_amount') }} :</strong>
                                        </td>
                                        <td>
                                            - {{ exchange_rate($order->deposit_amount,$order->exchange_rate) }} {{ $order->symbol }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>{{ __('cruds.order.extra.shipping_country_cost') }} :</strong>
                                        </td>
                                        <td>
                                            + {{ exchange_rate($order->shipping_country_cost,$order->exchange_rate) }} {{ $order->symbol }}
                                        </td>
                                    </tr>
                                    <tr style="background: #34828285">
                                        <td>
                                            <strong>{{ __('cruds.order.fields.total_cost') }} :</strong>
                                        </td>
                                        <td class="text-bold h4">
                                            = {{ exchange_rate($order->calc_total_for_client(),$order->exchange_rate) }} {{ $order->symbol }}
                                            @if($order->discount_code != null)
                                                <br>
                                                <span class="badge badge-purple">
                                                    كود الخصم {{ $order->discount_code }}
                                                    /
                                                    {{ exchange_rate($order->calc_discount(),$order->exchange_rate) }} {{ $order->symbol }}
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
                                {{ __('cruds.order.fields.id') }}
                            </th>
                            <td>
                                {{ $order->id ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ __('cruds.order.fields.order_num') }}
                            </th>
                            <td>
                                {{ $order->order_num ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ __('cruds.order.fields.delivery_status') }}
                            </th>
                            <td>
                                {{ $order->delivery_status ? __('global.delivery_status.status.' . $order->delivery_status) : '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ __('cruds.order.fields.deposit_amount') }}
                            </th>
                            <td>
                                {{ $order->deposit_amount ? dashboard_currency($order->deposit_amount) : '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ __('cruds.order.fields.deposit_type') }}
                            </th>
                            <td>
                                {{ $order->deposit_type ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ __('cruds.order.fields.created_at') }}
                            </th>
                            <td>
                                {{ $order->created_at ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th> 
                                {{ __('cruds.order.fields.date_of_receiving_order') }}
                            </th>
                            <td>
                                {{ $order->date_of_recieving_money ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ __('cruds.order.fields.excepected_deliverd_date') }}
                            </th>
                            <td>
                                {{ $order->excepected_deliverd_date ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ __('cruds.order.fields.total_cost_by_seller') }}
                            </th>
                            <td>
                                {{ $order->total_cost_by_seller ?? '' }}
                            </td> 
                        </tr> 
                        <tr>
                            <th>
                                {{ __('cruds.order.fields.free_shipping') }}
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
                                {{ __('cruds.order.fields.shipping_cost_by_seller') }}
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
        {{ __('global.back_to_list') }}
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

        function edit_order_detail(id){
            $.post('{{ route('admin.orders.edit_order_detail') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);
            });
        }
        function add_order_detail(id){
            $.post('{{ route('admin.orders.add_order_detail') }}', {
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