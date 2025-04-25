@extends('layouts.admin')
@section('content')  

    <div class="row"> 
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">  
                    <form action="" method="GET" id="sort_orders"> 
                        <div class="row">
            
                            <div class="col-md-4"> 
                                <input type="text" class="form-control mb-2 @isset($client_name) isset @endisset" id="client_name" name="client_name"
                                    @isset($client_name) value="{{ $client_name }}" @endisset placeholder="{{ __('cruds.order.fields.client_name') }}">  
                            </div>  
                            <div class="col-md-4"> 
                                    
                                <input type="text" class="form-control mb-2 @isset($order_num) isset @endisset" id="order_num" name="order_num" 
                                    @isset($order_num) value="{{ $order_num }}" @endisset placeholder="{{ __('cruds.order.fields.order_num') }}">   
                            </div>  
                            <div class="col-md-4"> 
                                    
                                <input type="text" class="form-control mb-2 @isset($phone) isset @endisset" id="phone" name="phone"
                                    @isset($phone) value="{{ $phone }}" @endisset placeholder="{{ __('cruds.order.fields.phone_number') }}">
                            </div>  
                        </div> 
                        <div class="row">
                            <div class="col-md-3">
                                <input type="submit" value="{{ __('global.search') }}" name="search" class="btn btn-success btn-rounded btn-block">
                            </div> 
                            
                            <div class="col-md-2">
                                <a class="btn btn-warning btn-rounded btn-block"  href="{{ route('admin.orders.abondoned') }}">{{ __('global.reset') }}</a>
                            </div>  
                        </div> 
                    </form>
            
                </div>
            </div>
            
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ __('global.list') }} {{ __('cruds.order.title') }} 
        </div>

        <div class="card-body">
            <table class="table table-bordered datatable table-responsive-lg table-responsive-md table-responsive-sm">
                <thead>
                    <tr>
                        <th>
                            {{ __('global.extra.client') }}
                        </th>
                        <th>
                            {{ __('global.extra.dates') }}
                        </th>
                        <th>
                            {{ __('cruds.order.fields.shipping_address') }}
                        </th>
                        <th>
                            {{ __('cruds.order.fields.total_cost') }}
                        </th> 
                        <th>
                            {{ __('global.extra.stages') }}
                        </th>
                        <th>
                            {{ __('cruds.order.fields.note') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr data-entry-id="{{ $order->id }}" style="background: #1d1c1c">
                            <td> 
                                <span class="order_num badge rounded-pill 
                                    @if($order->website_setting_id == 2) order_num_ertgal 
                                    @elseif($order->website_setting_id == 3) order_num_figures 
                                    @elseif($order->website_setting_id == 4) order_num_shirti 
                                    @else order_num_ebtekar @endif text-white mb-1" style="cursor: pointer" onclick="show_logs('App\\Models\\Order','{{ $order->id }}','Order')">
                                    @if($order->printing_times == 0) 
                                        <span class="badge rounded-pill text-bg-primary text-white">
                                            new
                                        </span>
                                    @else
                                        <span class="badge rounded-pill text-bg-primary text-white">
                                            {{ $order->printing_times }}
                                        </span>
                                    @endif
                                    {{ $order->order_num ?? '' }}
                                </span>
                                <div style="display:flex;justify-content:space-between;color:#c62525">
                                    <div>
                                        {{ $order->client_name ?? '' }}
                                    </div>
                                    <div>
                                        {{ $order->phone_number ?? '' }}
                                        <br>
                                        {{ $order->phone_number_2 ?? '' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge text-bg-primary text-white mb-1">
                                    {{ __('cruds.order.fields.created_at') }}
                                    <br> {{ $order->created_at }}
                                </span>
                                @if($order->date_of_receiving_order)
                                    <br>
                                    <span class="badge text-bg-light mb-1">
                                        {{ __('cruds.order.fields.date_of_receiving_order') }}
                                        <br> {{ $order->date_of_receiving_order }}
                                    </span>
                                @endif
                                @if($order->send_to_delivery_date)
                                    <br>
                                    <span class="badge text-bg-info text-white mb-1">
                                        {{ __('cruds.order.fields.send_to_delivery_date') }}
                                        <br> {{ $order->send_to_delivery_date }}
                                    </span>
                                @endif
                                @if($order->send_to_playlist_date)
                                    <br>
                                    <span class="badge text-bg-dark text-white mb-1">
                                        {{ __('cruds.order.fields.send_to_playlist_date') }}
                                        <br> {{ $order->send_to_playlist_date }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill text-bg-warning  mb-1">
                                    {{ $order->shipping_country ? $order->shipping_country->name : '' }}
                                </span>
                                <span style="color:white">

                                    {{ $order->shipping_address ?? '' }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;justify-content:space-between">
                                    @if($order->deposit_amount > 0)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ __('cruds.order.fields.deposit_amount') }}
                                            <br>
                                            {{ exchange_rate($order->deposit_amount,$order->exchange_rate) }} {{ $order->symbol }}
                                        </span>
                                    @endif
                                    @if($order->commission > 0)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ __('cruds.order.fields.commission') }}
                                            <br>
                                            {{ exchange_rate(($order->commission + $order->extra_commission),$order->exchange_rate) }} {{ $order->symbol }}
                                        </span>
                                    @endif
                                </div>
                                <div style="display:flex;justify-content:space-between">
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ __('cruds.order.fields.shipping_country_cost') }}
                                        <br>
                                        {{ exchange_rate($order->shipping_country_cost,$order->exchange_rate) }} {{ $order->symbol }}
                                    </span>
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ __('cruds.order.fields.total_cost') }}
                                        <br>
                                        {{ exchange_rate($order->total_cost,$order->exchange_rate) }} {{ $order->symbol }}
                                    </span>
                                </div>
                                
                                <div style="display:flex;justify-content:center">
                                    @if($order->discount > 0)
                                        <span class="badge rounded-pill text-bg-info  mb-1">
                                            <br>
                                            {{ __('cruds.order.fields.discount') }}
                                            <br>
                                            {{ exchange_rate($order->discount,$order->exchange_rate) }} {{ $order->symbol }}
                                        </span>
                                    @endif
                                    <span class="badge rounded-pill text-bg-success text-white mb-1 total_cost"> 
                                        = {{ exchange_rate($order->calc_total_for_client(),$order->exchange_rate)  }} {{ $order->symbol }}
                                    </span>
                                </div>  
                            </td> 
                            <td>
                                <span
                                    class="badge text-bg-{{ __('global.payment_type.colors.' . $order->payment_type) }} mb-1">
                                    {{ $order->payment_type ? __('global.payment_type.status.' . $order->payment_type) : '' }}
                                </span>
                                <br> 
                                <span
                                    class="badge text-bg-{{ __('global.payment_status.colors.' . $order->payment_status) }} mb-1">
                                    {{ $order->payment_status ? __('global.payment_status.status.' . $order->payment_status) : '' }}
                                </span> 
                            </td>
                            <td>
                                {{ $order->note ?? '' }}
                            </td>
                            <td >
                                @can('order_show')
                                    <a class="btn btn-primary"
                                        href="{{ route('admin.orders.show', $order->id) }}">
                                        {{ __('global.view') }} 
                                    </a>
                                @endcan  
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                No data available in table
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $orders->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    
    <script> 
        function sort_orders(el) {
            $('#sort_orders').submit();
        } 
    </script>
@endsection
