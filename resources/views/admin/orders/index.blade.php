@extends('layouts.admin')
@section('content')

    <div class="row">
        <div class="col-xl-3 col-md-12">
            <div class="card">
                <div class="card-body">
                    <b>{{ trans('global.statistics') }} {{ trans('cruds.order.title') }}</b>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body p-3 d-flex align-items-center" style="box-shadow: 1px 2px 10px #8080803d;border-radius: 9px;">
                                    <div class="bg-dark text-white p-3 me-3">
                                        <i class="fas fa-list-ol"></i>
                                    </div>
                                    <div>
                                        <div class="fs-6 fw-semibold text-dark">{{ $orders->total() }}</div>
                                        <div class="text-medium-emphasis text-uppercase fw-semibold small">عدد الفواتير
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body p-3 d-flex align-items-center" style="box-shadow: 1px 2px 10px #8080803d;border-radius: 9px;">
                                    <div class="bg-primary text-white p-3 me-3">
                                        <i class="fab fa-deploydog"></i>
                                    </div>
                                    <div>
                                        <div class="fs-6 fw-semibold text-primary">{{ dashboard_currency(round($statistics['total_deposit'])) }}</div>
                                        <div class="text-medium-emphasis text-uppercase fw-semibold small">العربون
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body p-3 d-flex align-items-center" style="box-shadow: 1px 2px 10px #8080803d;border-radius: 9px;">
                                    <div class="bg-info text-white p-3 me-3">
                                        <i class="far fa-money-bill-alt"></i>
                                    </div>
                                    <div>
                                        <div class="fs-6 fw-semibold text-info">{{ dashboard_currency(round($statistics['total_total_cost'])) }}</div>
                                        <div class="text-medium-emphasis text-uppercase fw-semibold small">مجموع
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <!-- /.col-->
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body p-3 d-flex align-items-center" style="box-shadow: 1px 2px 10px #8080803d;border-radius: 9px;">
                                    <div class="bg-danger text-white p-3 me-3">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                    <div>
                                        <div class="fs-6 fw-semibold text-danger">{{ dashboard_currency(round($statistics['total_commission'])) }}</div>
                                        <div class="text-medium-emphasis text-uppercase fw-semibold small">نسبة الربح
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-md-12">
            @include('admin.orders.partials.search')
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('global.list') }} {{ trans('cruds.order.title') }} 
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm">
                <thead>
                    <tr>
                        <th>
                            {{ trans('global.extra.client') }}
                        </th>
                        <th>
                            {{ trans('global.extra.dates') }}
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.shipping_address') }}
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.total_cost') }}
                        </th>
                        <th>
                            {{ trans('global.extra.statuses') }}
                        </th>
                        <th>
                            {{ trans('global.extra.stages') }}
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.note') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr data-entry-id="{{ $order->id }}"  @if($order->quickly)  class="quickly"  @endif>
                            <td>
                                @if($order->printing_times == 0) 
                                    <span class="badge rounded-pill text-bg-primary text-white">
                                        new
                                    </span>
                                @endif
                                <span class="badge rounded-pill @if($order->order_type == 'customer') text-bg-danger @else text-bg-info @endif text-white mb-1" style="cursor: pointer" onclick="show_logs('App\\Models\\Order','{{ $order->id }}','Order')">
                                    {{ $order->order_num ?? '' }}
                                </span>
                                <div style="display:flex;justify-content:space-between">
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
                                    {{ trans('cruds.order.fields.created_at') }}
                                    <br> {{ $order->created_at }}
                                </span>
                                @if($order->date_of_receiving_order)
                                    <br>
                                    <span class="badge text-bg-light mb-1">
                                        {{ trans('cruds.order.fields.date_of_receiving_order') }}
                                        <br> {{ $order->date_of_receiving_order }}
                                    </span>
                                @endif
                                @if($order->send_to_delivery_date)
                                    <br>
                                    <span class="badge text-bg-info text-white mb-1">
                                        {{ trans('cruds.order.fields.send_to_delivery_date') }}
                                        <br> {{ $order->send_to_delivery_date }}
                                    </span>
                                @endif
                                @if($order->send_to_playlist_date)
                                    <br>
                                    <span class="badge text-bg-dark text-white mb-1">
                                        {{ trans('cruds.order.fields.send_to_playlist_date') }}
                                        <br> {{ $order->send_to_playlist_date }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill text-bg-warning  mb-1">
                                    {{ $order->shipping_country ? $order->shipping_country->name : '' }}
                                </span>
                                {{ $order->shipping_address ?? '' }}
                            </td>
                            <td>
                                <div style="display:flex;justify-content:space-between">
                                    @if($order->deposit_amount > 0)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ trans('cruds.order.fields.deposit_amount') }}
                                            <br>
                                            {{ exchange_rate($order->deposit_amount,$order->exchange_rate) }} {{ $order->symbol }}
                                        </span>
                                    @endif
                                    @if($order->extra_commission > 0)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ trans('cruds.order.fields.extra_commission') }}
                                            <br>
                                            {{ exchange_rate($order->extra_commission,$order->exchange_rate) }} {{ $order->symbol }}
                                        </span>
                                    @endif
                                </div>
                                <div style="display:flex;justify-content:space-between">
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ trans('cruds.order.fields.shipping_country_cost') }}
                                        <br>
                                        {{ exchange_rate($order->shipping_country_cost,$order->exchange_rate) }} {{ $order->symbol }}
                                    </span>
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ trans('cruds.order.fields.total_cost') }}
                                        <br>
                                        {{ exchange_rate($order->total_cost,$order->exchange_rate) }} {{ $order->symbol }}
                                    </span>
                                </div>
                                
                                <div style="display:flex;justify-content:center">
                                    @if($order->discount > 0)
                                        <span class="badge rounded-pill text-bg-info  mb-1">
                                            {{ trans('cruds.order.fields.discount') }}
                                            <br>
                                            {{ exchange_rate($order->discount,$order->exchange_rate) }} {{ $order->symbol }}
                                        </span>
                                    @endif
                                    <span class="badge rounded-pill text-bg-success text-white mb-1"> 
                                        = {{ exchange_rate($order->calc_total_for_client(),$order->exchange_rate)  }} {{ $order->symbol }}
                                    </span>
                                </div> 
                            </td>
                            <td> 
                                <div>
                                    <div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;"
                                        class="badge text-bg-light mb-1">
                                        <span>
                                            {{ trans('cruds.order.fields.quickly') }}
                                        </span>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input onchange="update_statuses(this,'quickly')" value="{{ $order->id }}"
                                                type="checkbox" class="c-switch-input"
                                                {{ $order->quickly ? 'checked' : null }}>
                                            <span class="c-switch-slider"></span>
                                        </label>
                                    </div>
                                    <div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;"
                                        class="badge text-bg-light mb-1">
                                        <span>
                                            {{ trans('cruds.order.fields.calling') }}
                                        </span>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input onchange="update_statuses(this,'calling')" value="{{ $order->id }}"
                                                type="checkbox" class="c-switch-input"
                                                {{ $order->calling ? 'checked' : null }}>
                                            <span class="c-switch-slider"></span>
                                        </label>
                                    </div>
                                </div> 
                            </td>
                            <td>
                                <span
                                    class="badge text-bg-{{ trans('global.delivery_status.colors.' . $order->delivery_status) }} mb-1">
                                    {{ $order->delivery_status ? trans('global.delivery_status.status.' . $order->delivery_status) : '' }}
                                </span>
                                <span
                                    class="badge text-bg-{{ trans('global.payment_status.colors.' . $order->payment_status) }} mb-1">
                                    {{ $order->payment_status ? trans('global.payment_status.status.' . $order->payment_status) : '' }}
                                </span>
                                @if($order->playlist_status == 'pending')
                                    <button class="btn btn-success btn-sm rounded-pill" onclick="playlist_users('{{$order->id}}','order')">أرسال للديزاينر</button>
                                @else  
                                    <span onclick="playlist_users('{{$order->id}}','order')" style="cursor: pointer"
                                        class="badge text-bg-{{ trans('global.playlist_status.colors.' . $order->playlist_status) }} mb-1">
                                        {{ $order->playlist_status ? trans('global.playlist_status.status.' . $order->playlist_status) : '' }}
                                    </span>
                                @endif
                                <hr>
                                <span class="badge text-bg-danger text-white mb-1">
                                    {{ trans('cruds.order.fields.added_by') }}
                                    =>
                                    {{ $order->user->name ?? '' }}
                                </span>
                                @if($order->delivery_man)
                                    <span class="badge text-bg-dark text-white mb-1">
                                        {{ trans('cruds.order.fields.delivery_man_id') }}
                                        =>
                                        {{ $order->delivery_man->name ?? '' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{ $order->note ?? '' }}
                            </td>
                            <td >
                                @can('order_show')
                                    <a class="btn btn-primary"
                                        href="{{ route('admin.orders.show', $order->id) }}">
                                        {{ trans('global.view') }} 
                                    </a>
                                @endcan 
                                @can('order_print')
                                    <a class="btn btn-success" target="_blanc"
                                        href="{{ route('admin.orders.print', $order->id) }}">
                                        {{ trans('global.print') }} 
                                    </a>
                                @endcan

                                @can('order_delete')
                                    <?php $route = route('admin.orders.destroy', $order->id); ?>
                                    <a class="btn btn-danger"
                                        href="#" onclick="deleteConfirmation('{{$route}}')">
                                        {{ trans('global.delete') }} 
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

        
        function update_statuses(el,type){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.orders.update_statuses') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status, type:type}, function(data){
                if(data == 1){
                    showAlert('success', 'Success', '');
                }else{
                    showAlert('danger', 'Something went wrong', '');
                }
            });
        }
    </script>
@endsection
