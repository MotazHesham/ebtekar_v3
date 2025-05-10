@extends('layouts.admin')
@section('content')

    <div class="row mb-3 text-center">

        <div class="col-md-3">
            <a class="btn btn-warning" href="#" data-toggle="modal" data-target="#uploadFedexModal">
                {{ __('global.extra.upload_fedex') }}
            </a>
        </div>
        <div class="col-md-3">
        </div> 
        <div class="col-md-3">
            <a class="btn btn-info" href="#" data-toggle="modal" data-target="#productsReportModal">
                تقارير المنتجات
            </a>
        </div>
        <div class="col-md-3">
            <a class="btn btn-danger" href="{{ route('admin.orders.abondoned')}}">
                Abandoned Checkout
            </a>
        </div>
    </div>

    <!-- Products Report Modal -->
    <div class="modal fade" id="productsReportModal" tabindex="-1" aria-labelledby="productsReportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productsReportModalLabel"> </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-3 form-group">
                            <label class="control-label" for="start_date">بداية التاريخ</label>
                            <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text"
                                name="start_date" id="start_date" value="{{ $start_date ?? '' }}" required>
                        </div>
                        <div class="col-3 form-group">
                            <label class="control-label" for="end_date">نهاية التاريخ</label>
                            <input class="form-control date {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text"
                                name="end_date" id="end_date" value="{{ $end_date ?? '' }}" required>
                        </div>
                        <div class="col-3 form-group">
                            <label class="control-label" >أختر الموقع</label>
                            <select class="form-control " style="width: 200px" name="website_setting_id" >
                                <option value="">أختر الموقع</option>
                                @foreach ($websites as $id => $entry)
                                    <option value="{{ $id }}" >
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="control-label">&nbsp;</label><br>
                            <button class="btn btn-primary" type="button" onclick="products_report()">{{ __('global.filterDate') }}</button> 
                        </div>
                    </div> 
                    <hr>
                    <div id="products-report-div">
                        {{-- ajax request --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Upload Fedex Modal -->
    <div class="modal fade" id="uploadFedexModal" tabindex="-1" aria-labelledby="uploadFedexModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadFedexModalLabel"> </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.orders.upload_fedex') }}" method="POST" enctype="multipart/form-data">
                        @csrf  
                        <div class="form-group">
                            <label class="required">النوع</label>
                            <select name="type" class="form-control" id="" required>
                                <option value="done">التسليم</option>
                                <option value="supplied">التوريد</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required" for="uploaded_file">{{ __('cruds.excelFile.fields.uploaded_file') }}</label>
                            <div class="needsclick dropzone {{ $errors->has('uploaded_file') ? 'is-invalid' : '' }}" id="uploaded_file-dropzone">
                            </div>
                            @if($errors->has('uploaded_file'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('uploaded_file') }}
                                </div>
                            @endif
                            <span class="help-block">{{ __('cruds.excelFile.fields.uploaded_file_helper') }}</span>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-success">{{ __('global.continue') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-12">
            <div class="card">
                <div class="card-body">
                    <b>{{ __('global.statistics') }} {{ __('cruds.order.title') }}</b>
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
                            {{ __('global.extra.statuses') }}
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
                        <tr data-entry-id="{{ $order->id }}"  @if($order->quickly)  class="quickly"  @endif>
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
                                {{ $order->shipping_address ?? '' }}
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
                                <div style="display: flex;justify-content: space-between;">
                                    <div class="badge text-bg-light mb-1" style="margin: 0px 3px;">
                                        <span>
                                            {{ __('cruds.receiptSocial.fields.done') }}
                                        </span>
                                        <br>
                                        <div id="done-{{$order->id}}">
                                            @if($order->done)
                                                <i class="far fa-check-circle" style="padding: 5px; font-size: 20px; color: green;"></i>
                                            @else
                                            
                                                @if(Gate::allows('done'))
                                                    <label class="c-switch c-switch-pill c-switch-success">
                                                        <input onchange="update_statuses(this,'done')" value="{{ $order->id }}"
                                                            type="checkbox" class="c-switch-input"
                                                            {{ $order->done ? 'checked' : null }}>
                                                        <span class="c-switch-slider"></span>
                                                    </label>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="badge text-bg-light mb-1" style="margin: 0px 3px;">
                                        <span>
                                            {{ __('cruds.order.fields.supplied') }}
                                        </span>
                                        <br>
                                        <div id="supplied-{{$order->id}}">
                                            @if($order->supplied)
                                                <i class="far fa-check-circle" style="padding: 5px; font-size: 20px; color: green;"></i>
                                            @else
                                            
                                                @if(Gate::allows('supplied'))
                                                    <label class="c-switch c-switch-pill c-switch-success">
                                                        <input onchange="update_statuses(this,'supplied')" value="{{ $order->id }}"
                                                            type="checkbox" class="c-switch-input"
                                                            {{ $order->supplied ? 'checked' : null }}>
                                                        <span class="c-switch-slider"></span>
                                                    </label>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td> 
                                <div>
                                    <div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;"
                                        class="badge text-bg-light mb-1">
                                        <span>
                                            {{ __('cruds.order.fields.quickly') }}
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
                                            {{ __('cruds.order.fields.calling') }}
                                        </span>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input onchange="update_statuses(this,'calling')" value="{{ $order->id }}"
                                                type="checkbox" class="c-switch-input"
                                                {{ $order->calling ? 'checked' : null }}>
                                            <span class="c-switch-slider"></span>
                                        </label>
                                    </div>
                                    <div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;"
                                        class="badge text-bg-light mb-1">
                                        <span>
                                            مرتجع
                                        </span>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input onchange="update_statuses(this,'returned')" value="{{ $order->id }}"
                                                type="checkbox" class="c-switch-input"
                                                {{ $order->returned ? 'checked' : null }}>
                                            <span class="c-switch-slider"></span>
                                        </label>
                                    </div>
                                </div> 
                            </td>
                            <td>
                                <span
                                    class="badge text-bg-{{ __('global.payment_type.colors.' . $order->payment_type) }} mb-1">
                                    {{ $order->payment_type ? __('global.payment_type.status.' . $order->payment_type) : '' }}
                                </span>
                                <br>
                                <span
                                    class="badge text-bg-{{ __('global.delivery_status.colors.' . $order->delivery_status) }} mb-1">
                                    {{ $order->delivery_status ? __('global.delivery_status.status.' . $order->delivery_status) : '' }}
                                </span>
                                <span
                                    class="badge text-bg-{{ __('global.payment_status.colors.' . $order->payment_status) }} mb-1">
                                    {{ $order->payment_status ? __('global.payment_status.status.' . $order->payment_status) : '' }}
                                </span>
                                @can('hold')
                                    <form action="{{ route('admin.orders.update_statuses') }}" method="POST" style="display: inline" id="hold-form-{{ $order->id }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $order->id }}">
                                        <input type="hidden" name="type" value="hold">
                                        <input type="hidden" name="hold_reason" id="hold-reason-{{ $order->id }}" value="{{ $order->hold_reason }}">
                                        @if($order->hold == 0)
                                            <input type="hidden" name="status" value="1"> 
                                            <button type="button" class="btn btn-dark btn-sm rounded-pill" onclick="showHoldModal('{{ $order->id }}','{{ $order->hold_reason }}')">Hold</button>
                                        @else 
                                            <input type="hidden" name="status" value="0">
                                            <button type="submit" class="btn btn-warning btn-sm rounded-pill">UnHold </button>
                                            @if($order->hold_reason)
                                                <span class="badge bg-info text-white" style="cursor: pointer" 
                                                    data-toggle="tooltip" 
                                                    data-placement="top" 
                                                    title="{{ $order->hold_reason }}">
                                                    <i class="fas fa-info-circle"></i> Hold Reason
                                                </span>
                                            @endif 
                                        @endif
                                    </form>
                                @endcan
                                @if($order->playlist_status == 'pending')
                                    <button class="btn btn-success btn-sm rounded-pill" onclick="playlist_users('{{$order->id}}','order')">أرسال للديزاينر</button>
                                @else  
                                    <span onclick="playlist_users('{{$order->id}}','order')" 
                                        class="playlist_status badge text-bg-{{ __('global.playlist_status.colors.' . $order->playlist_status) }} mb-1">
                                        {{ $order->playlist_status ? __('global.playlist_status.status.' . $order->playlist_status) : '' }}
                                    </span>
                                @endif
                                <hr>
                                <span class="badge text-bg-danger text-white mb-1">
                                    {{ __('cruds.order.fields.added_by') }}
                                    =>
                                    {{ $order->user->name ?? '' }}
                                </span>
                                @if($order->delivery_man)
                                    <span class="badge text-bg-dark text-white mb-1">
                                        {{ __('cruds.order.fields.delivery_man_id') }}
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
                                        {{ __('global.view') }} 
                                    </a>
                                @endcan 
                                @can('order_print')
                                    <a class="btn btn-success" target="_blanc"
                                        href="{{ route('admin.orders.print', $order->id) }}">
                                        {{ __('global.print') }} 
                                    </a>
                                @endcan
                                
                                @if(!$order->hold || auth()->user()->is_admin)
                                    @can('order_edit')
                                        <a class="btn btn-info" 
                                            href="{{ route('admin.orders.edit', $order->id) }}">
                                            {{ __('global.edit') }} 
                                        </a>
                                    @endcan

                                    @can('order_delete')
                                        <?php $route = route('admin.orders.destroy', $order->id); ?>
                                        <a class="btn btn-danger"
                                            href="#" onclick="deleteConfirmation('{{$route}}')">
                                            {{ __('global.delete') }} 
                                        </a>
                                    @endcan
                                @endif
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
    
    <!-- Hold Reason Modal -->
    <div class="modal fade" id="holdReasonModal" tabindex="-1" aria-labelledby="holdReasonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="holdReasonModalLabel">Hold Reason</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="holdReason">Please enter reason for hold:</label>
                        <textarea class="form-control" id="holdReason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitHoldForm()">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    
    <script>
        Dropzone.options.uploadedFileDropzone = {
            url: '{{ route('admin.excel-files.storeMedia') }}',
            maxFilesize: 5, // MB
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 5
            },
            success: function(file, response) {
                $('form').find('input[name="uploaded_file"]').remove()
                $('form').append('<input type="hidden" name="uploaded_file" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="uploaded_file"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            }, 
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
    <script> 
    
        function products_report(){
            $.post('{{ route('admin.orders.products_report') }}', {
                _token: '{{ csrf_token() }}',
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                website_setting_id: $('#productsReportModal select').val(),
            }, function(data) {
                $('#products-report-div').html(null); 
                $('#products-report-div').html(data); 
            });
        }

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
                if (data['status'] == '1') {
                    showAlert('success', 'Success', data['message']);
                } else if(data['status'] == '2') { 
                    $('#done-'+el.value).html(data['first']);
                    showAlert('success', 'Success', data['message']);
                } else if(data['status'] == '3') { 
                    $('#supplied-'+el.value).html(data['first']);
                    showAlert('success', 'Success', data['message']);
                }
            });
        }
    </script>
    
    <script>
        let currentReceiptId = null;

        function showHoldModal(receiptId, existingReason) {
            currentReceiptId = receiptId; 
            $('#holdReason').val(existingReason);
            $('#holdReasonModal').modal('show');
        }

        function submitHoldForm() {
            const reason = $('#holdReason').val();
            if (!reason.trim()) {
                alert('Please enter a reason for hold');
                return;
            }
            
            $(`#hold-reason-${currentReceiptId}`).val(reason);
            $(`#hold-form-${currentReceiptId}`).submit();
            $('#holdReasonModal').modal('hide');
        }
    </script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
