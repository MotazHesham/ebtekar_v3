@extends('layouts.admin')
@section('content')
<div class="row mb-3 text-center">
    @can('receipt_client_create') 
        <div class="col-lg-4">
            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#phoneModal">
                {{ trans('global.add') }} {{ trans('cruds.receiptClient.title_singular') }}
            </a>
        </div> 
    @endcan
    
    @can('receipt_client_product_access')
        <div class="col-md-4">
            <a class="btn btn-info" href="{{ route('admin.receipt-client-products.index') }}">
                {{ trans('cruds.receiptClientProduct.title') }}
            </a>
        </div>
    @endcan

    @if(isset($deleted))
        <div class="col-md-3">
            <a class="btn btn-dark" href="{{ route('admin.receipt-clients.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    @else 
    
        @if(Gate::allows('soft_delete'))
            <div class="col-md-3">
                <a class="btn btn-danger" href="{{ route('admin.receipt-clients.index',['deleted' => 1]) }}">
                    {{ trans('global.extra.deleted_receipts') }}
                </a>
            </div>
        @endif
    @endif
</div>

    <!-- Phone Modal -->
    <div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phoneModalLabel">{{ trans('global.add') }}
                        {{ trans('cruds.receiptClient.title_singular') }}</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.receipt-clients.create') }}">
                        <div class="row mb-3">
                            @foreach($websites as $id => $entry)
                                <div class="form-check form-check-inline col">
                                    <input class="form-check-input" type="radio" name="website_setting_id" id="{{$id}}" value="{{$id}}" required @if($id == auth()->user()->website_setting_id) checked @endif>
                                    <label class="form-check-label" for="{{$id}}">{{$entry}}</label>
                                </div>
                            @endforeach 
                        </div>
                        <input type="text" name="phone_number" class="form-control" required
                            placeholder="{{ trans('cruds.receiptClient.fields.phone_number') }}"
                            onkeyup="searchByPhone(this)">
                        <div id="table-receipts">
                            {{-- ajax call --}}
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @if(Gate::allows('statistics_receipts'))
            <div class="col-xl-3 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <b>{{ trans('global.statistics') }} {{ trans('cruds.receiptClient.title') }}</b>
                        <hr> 
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="card">
                                    <div class="card-body p-3 d-flex align-items-center" style="box-shadow: 1px 2px 10px #8080803d;border-radius: 9px;">
                                        <div class="bg-dark text-white p-3 me-3">
                                            <i class="fas fa-list-ol"></i>
                                        </div>
                                        <div>
                                            <div class="fs-6 fw-semibold text-dark">{{ $receipts->total() }}</div>
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
                                            <div class="fs-6 fw-semibold text-primary">{{ dashboard_currency($statistics['total_deposit']) }}</div>
                                            <div class="text-medium-emphasis text-uppercase fw-semibold small">العربون
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col-->
                            <div class="col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-body p-3 d-flex align-items-center" style="box-shadow: 1px 2px 10px #8080803d;border-radius: 9px;">
                                        <div class="bg-info text-white p-3 me-3">
                                            <i class="far fa-money-bill-alt"></i>
                                        </div>
                                        <div>
                                            <div class="fs-6 fw-semibold text-info">{{ dashboard_currency($statistics['total_total_cost']) }}</div>
                                            <div class="text-medium-emphasis text-uppercase fw-semibold small">مجموع
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
        @endif
        <div class="@if(Gate::allows('statistics_receipts')) col-xl-9 @else col-xl-12 @endif col-md-12">
            @include('admin.receiptClients.partials.search')
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            {{ trans('global.list') }} {{ trans('cruds.receiptClient.title') }}
            @isset($deleted)
                {{ trans('global.deleted') }}
            @endisset
        </div>

        <div class="card-body">
            <table class="table table-bordered datatable table-responsive-lg table-responsive-md table-responsive-sm">
                <thead>
                    <tr>

                        <th>#</th>
                        <th>
                            {{ trans('global.extra.client') }}
                        </th>
                        <th>
                            {{ trans('global.extra.dates') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptClient.fields.total_cost') }}
                        </th>
                        <th>
                            {{ trans('global.extra.statuses') }}
                        </th>
                        <th>
                            {{ trans('global.extra.stages') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptClient.fields.note') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($receipts as $key => $receipt)
                        <tr data-entry-id="{{ $receipt->id }}" class=" @if($receipt->quickly) quickly @elseif($receipt->done) done @endif">
                            <td>
                                <br>{{ ($key+1) + ($receipts->currentPage() - 1)*$receipts->perPage() }}
                            </td>
                            <td> 
                                <span class="order_num badge rounded-pill
                                    @if($receipt->website_setting_id == 2) order_num_ertgal 
                                    @elseif($receipt->website_setting_id == 3) order_num_figures 
                                    @elseif($receipt->website_setting_id == 4) order_num_shirti 
                                    @elseif($receipt->website_setting_id == 5) order_num_martobia
                                    @else order_num_ebtekar @endif text-white mb-1" 
                                    onclick="show_logs('App\\Models\\ReceiptClient','{{ $receipt->id }}','receiptClient')">
                                    @if($receipt->printing_times == 0) 
                                        <span class="badge rounded-pill text-bg-primary text-white">
                                            new
                                        </span>
                                    @else
                                        <span class="badge rounded-pill text-bg-primary text-white">
                                            {{ $receipt->printing_times }}
                                        </span>
                                    @endif
                                    {{ $receipt->order_num ?? '' }}
                                </span>
                                <div style="display:flex;justify-content:space-between">
                                    <div>
                                        <b>{{ $receipt->client_name ?? '' }} </b>
                                    </div>
                                    <div>
                                        <b>{{ $receipt->phone_number ?? '' }}</b>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge text-bg-primary text-white mb-1">
                                    {{ trans('cruds.receiptClient.fields.created_at') }}
                                    <br> {{ $receipt->created_at }}
                                </span>
                                @if ($receipt->date_of_receiving_order)
                                    <br>
                                    <span class="badge text-bg-light mb-1">
                                        {{ trans('cruds.receiptClient.fields.date_of_receiving_order') }}
                                        <br> {{ $receipt->date_of_receiving_order }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;justify-content:space-between">
                                    @if($receipt->deposit)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ trans('cruds.receiptClient.fields.deposit') }}
                                            <br>
                                            {{ dashboard_currency($receipt->deposit) }}
                                        </span>
                                    @endif

                                    @if($receipt->discount)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ trans('cruds.receiptClient.fields.discount') }}
                                            <br>
                                            {{ $receipt->discount }}%
                                        </span>
                                    @endif
                                </div>
                                <div style="display:flex;justify-content:space-between">
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ trans('cruds.receiptClient.fields.total_cost') }}
                                        <br>
                                        {{ dashboard_currency($receipt->total_cost) }}
                                    </span>
                                    <span class="badge rounded-pill text-bg-success text-white mb-1 total_cost">
                                        = {{ dashboard_currency($receipt->calc_total_for_client()) }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex;justify-content: space-between;">
                                    <div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;"
                                        class="badge text-bg-light mb-1">
                                        <span>
                                            {{ trans('cruds.receiptClient.fields.quickly') }}
                                        </span>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input onchange="update_statuses(this,'quickly')" value="{{ $receipt->id }}"
                                                type="checkbox" class="c-switch-input"
                                                {{ $receipt->quickly ? 'checked' : null }}>
                                            <span class="c-switch-slider"></span>
                                        </label>
                                    </div>
                                    <div class="badge text-bg-light mb-1" style="margin: 0px 3px;">
                                        <span>
                                            {{ trans('cruds.receiptClient.fields.done') }}
                                        </span>
                                        <br>
                                        
                                        <div id="done-{{$receipt->id}}">
                                            @if($receipt->done)
                                                <i class="far fa-check-circle" style="padding: 5px; font-size: 20px; color: green;"></i>
                                            @else 
                                                @if(Gate::allows('done'))
                                                    <label class="c-switch c-switch-pill c-switch-success">
                                                        <input onchange="update_statuses(this,'done')" value="{{ $receipt->id }}"
                                                            type="checkbox" class="c-switch-input"
                                                            {{ $receipt->done ? 'checked' : null }}>
                                                        <span class="c-switch-slider"></span>
                                                    </label>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div> 
                                <div style="display:flex;justify-content:space-between">
                                    @if($receipt->deposit_type )
                                        <span class="badge rounded-pill text-bg-info text-white  mb-1">
                                            {{ trans('cruds.receiptSocial.fields.deposit_type') }}
                                            <br>
                                            {{ $receipt->deposit_type ? \App\Models\ReceiptSocial::DEPOSIT_TYPE_SELECT[$receipt->deposit_type] : '' }}
                                        </span>
                                    @endif
                                    @if($receipt->financial_account )
                                        <span class="badge rounded-pill text-bg-info text-white  mb-1">
                                            {{ trans('cruds.receiptSocial.fields.financial_account_id') }}
                                            <br>
                                            {{ $receipt->financial_account->account ?? '' }}
                                        </span>
                                    @endif
                                </div> 
                            <td>
                                <span class="badge text-bg-danger text-white mb-1">
                                    {{ trans('global.extra.created_by') }}
                                    =>
                                    {{ $receipt->staff->name ?? '' }}
                                </span>
                            </td>
                            <td>
                                {{ $receipt->note ?? '' }}
                            </td>
                            <td style="margin: 11px; padding: 50px 15px;">
                                <div class="c-header"
                                    style="background: #ffffff00;height: fit-content;min-height:20px;border-bottom:0px">
                                    <div class="dropdown text-center">
                                        <a style="cursor: pointer" class="dropdown-button"
                                            id="dropdown-menu-{{ $receipt->id }}" data-toggle="dropdown"
                                            data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                                            <span>
                                                <i class="far fa-edit" style="font-size:28px;color:black"></i>
                                                أجراءات
                                            </span>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdown-menu-{{ $receipt->id }}">

                                            @can('receipt_client_view_products')
                                                <a class="dropdown-item" style="cursor: pointer"
                                                    onclick="view_products('{{ $receipt->id }}')">
                                                    {{ trans('global.extra.view_products') }}
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @if(!isset($deleted))
                                                @can('receipt_client_add_product')
                                                    <a class="dropdown-item" style="cursor: pointer"
                                                        onclick="add_product('{{ $receipt->id }}')">
                                                        {{ trans('global.extra.add_product') }}
                                                        <i class="fas fa-plus-circle" style="color:lightseagreen"></i>
                                                    </a>
                                                @endcan
                                                @can('receipt_client_edit')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.receipt-clients.edit', $receipt->id) }}">
                                                        {{ trans('global.edit') }}
                                                        <i class="far fa-edit" style="color:cornflowerblue"></i>
                                                    </a>
                                                @endcan
                                                @can('receipt_client_print')
                                                    <a class="dropdown-item" target="print-frame"
                                                        href="{{ route('admin.receipt-clients.print', $receipt->id) }}">
                                                        {{ trans('global.print') }}
                                                        <i class="fas fa-print" style="color:yellowgreen"></i>
                                                    </a>
                                                @endcan
                                                @can('receipt_client_duplicate')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.receipt-clients.duplicate', $receipt->id) }}">
                                                        {{ trans('global.duplicate') }}
                                                        <i class="far fa-clone" style="color:blueviolet"></i>
                                                    </a>
                                                @endcan
                                                @can('receipt_client_receive_money')
                                                    <a class="dropdown-item" target="print-frame"
                                                        href="{{ route('admin.receipt-clients.receive_money', $receipt->id) }}">
                                                        {{ trans('global.receive_money') }}
                                                        <i class="fas fa-money-bill-wave" style="color:cadetblue"></i>
                                                    </a>
                                                @endcan 
                                            @else  
                                                @can('receipt_client_restore')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.receipt-clients.restore', $receipt->id) }}">
                                                        {{ trans('global.restore') }}
                                                        <i class="fas fa-undo" style="color:grey"></i>
                                                    </a>  
                                                @endcan
                                            @endif
                                            @can('receipt_client_delete')
                                                <?php $route = route('admin.receipt-clients.destroy', $receipt->id); ?>
                                                <a class="dropdown-item" href="#"
                                                    onclick="deleteConfirmation('{{ $route }}')">
                                                    {{ trans('global.delete') }}  @isset($deleted) {{ trans('global.permanently') }} @endisset
                                                    <i class="fas fa-trash-alt" style="color:darkred"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
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
                {{ $receipts->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script> 

        $(document).ready(function() {
            
            $('input[type=checkbox]').on('click',function() {     
                return confirm('are you sure?');
            });
            
            @if(session('store_receipt_id') && session('store_receipt_id') != null)
                add_product('{{session("store_receipt_id")}}') 
            @endif
            @if(session('update_receipt_id') && session('update_receipt_id') != null)
                view_products('{{session("update_receipt_id")}}') 
            @endif
        });
        
        function sort_receipt_client(el) {
            $('#sort_receipt_client').submit();
        }


        function update_statuses(el, type) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('admin.receipt-clients.update_statuses') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status,
                type: type
            }, function(data) {
                if (data['status'] == '1') {
                    showAlert('success', 'Success', data['message']);
                } else if(data['status'] == '2') { 
                    $('#done-'+el.value).html(data['first']);
                    showAlert('success', 'Success', data['message']);
                } 
            });
        }

        function add_product(id) {
            $.post('{{ route('admin.receipt-clients.add_product') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);

                // load the ckeditor to description
                var allEditors = document.querySelectorAll('.ckeditor');
                for (var i = 0; i < allEditors.length; ++i) {
                    ClassicEditor.create(
                        allEditors[i], {
                            extraPlugins: [SimpleUploadAdapter]
                        }
                    );
                }
            });
        }


        function edit_product(id) {
            $.post('{{ route('admin.receipt-clients.edit_product') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#AjaxModal2 .modal-dialog').html(null);
                $('#AjaxModal2').modal('show');
                $('#AjaxModal2 .modal-dialog').html(data);

                // load the ckeditor to description
                var allEditors = document.querySelectorAll('.ckeditor');
                for (var i = 0; i < allEditors.length; ++i) {
                    ClassicEditor.create(
                        allEditors[i], {
                            extraPlugins: [SimpleUploadAdapter]
                        }
                    );
                }
            });
        }

        function view_products(id) {
            $.post('{{ route('admin.receipt-clients.view_products') }}', {
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
