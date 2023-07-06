@extends('layouts.admin')
@section('content')
    <div class="row mb-3">
        @can('receipt_price_view_create')
            <div class="col-md-3">
                <a class="btn btn-success" href="{{ route('admin.receipt-price-views.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.receiptPriceView.title_singular') }}
                </a>
            </div>
        @endcan
        @if(isset($deleted))
            <div class="col-md-3">
                <a class="btn btn-dark" href="{{ route('admin.receipt-price-views.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        @else 
            <div class="col-md-3">
                <a class="btn btn-danger" href="{{ route('admin.receipt-price-views.index',['deleted' => 1]) }}">
                    {{ trans('global.extra.deleted_receipts') }}
                </a>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-12">
            <div class="card">
                <div class="card-body">
                    <b>{{ trans('global.statistics') }} {{ trans('cruds.receiptPriceView.title') }}</b>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
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
        <div class="col-xl-9 col-md-12">
            @include('admin.receiptPriceViews.partials.search')
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            {{ trans('global.list') }} {{ trans('cruds.receiptPriceView.title') }} 
            @isset($deleted)
                {{ trans('global.deleted') }}
            @endisset
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ReceiptPriceView">
                <thead>
                    <tr>
                        <th>
                            {{ trans('global.extra.client') }}
                        </th>
                        <th>
                            {{ trans('global.extra.dates') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.total_cost') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.place') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.relate_duration') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.supply_duration') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.payment') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.added_value') }}
                        </th>
                        <td>
                            &nbsp;
                        </td>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    

                    @forelse ($receipts as $receipt)
                        <tr data-entry-id="{{ $receipt->id }}">
                            <td>
                                @if ($receipt->printing_times == 0)
                                    <span class="badge rounded-pill text-bg-primary text-white">
                                        new
                                    </span>
                                @endif
                                <span class="badge rounded-pill @if($receipt->website_setting_id == 2) text-bg-dark @elseif($receipt->website_setting_id == 3) text-bg-info @elseif($receipt->website_setting_id == 4) text-bg-primary @else text-bg-danger @endif text-white mb-1" style="cursor: pointer"
                                    onclick="show_logs('App\\Models\\ReceiptPriceView','{{ $receipt->id }}','receiptPriceView')">
                                    {{ $receipt->order_num ?? '' }}
                                </span>
                                <div style="display:flex;justify-content:space-between">
                                    <div>
                                        {{ $receipt->client_name ?? '' }} 
                                    </div>
                                    <div>
                                        {{ $receipt->phone_number ?? '' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge text-bg-primary text-white mb-1">
                                    {{ trans('cruds.receiptPriceView.fields.created_at') }}
                                    <br> {{ $receipt->created_at }}
                                </span>
                                @if ($receipt->date_of_receiving_order)
                                    <br>
                                    <span class="badge text-bg-light mb-1">
                                        {{ trans('cruds.receiptPriceView.fields.date_of_receiving_order') }}
                                        <br> {{ $receipt->date_of_receiving_order }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;justify-content:space-between"> 
                                    @if($receipt->added_value)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ trans('cruds.receiptPriceView.fields.added_value') }}
                                            <br>
                                            {{ dashboard_currency($receipt->calc_added_value()) }}
                                        </span>
                                    @endif
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ trans('cruds.receiptPriceView.fields.total_cost') }}
                                        <br>
                                        {{ dashboard_currency($receipt->total_cost) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="badge rounded-pill text-bg-success text-white mb-1">
                                        = {{ dashboard_currency($receipt->calc_total_cost()) }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                {{ $receipt->place ?? '' }}
                            </td> 
                            <td>
                                {{ $receipt->relate_duration ?? '' }}
                            </td> 
                            <td>
                                {{ $receipt->supply_duration ?? '' }}
                            </td> 
                            <td>
                                {{ $receipt->payment ? dashboard_currency($receipt->payment) : '' }}
                            </td> 
                            <td>
                                @if($receipt->added_value)
                                    شامل
                                @else 
                                    غير شامل
                                @endif
                            </td>  
                            <td>
                                <span class="badge text-bg-danger text-white mb-1">
                                    {{ trans('global.extra.created_by') }}
                                    =>
                                    {{ $receipt->staff->name ?? '' }}
                                </span>
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

                                            @can('receipt_price_view_product_access')
                                                <a class="dropdown-item" style="cursor: pointer"
                                                    onclick="view_products('{{ $receipt->id }}')">
                                                    {{ trans('global.extra.view_products') }}
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @if(!isset($deleted))
                                                @can('receipt_price_view_product_create')
                                                    <a class="dropdown-item" style="cursor: pointer"
                                                        onclick="add_product('{{ $receipt->id }}')">
                                                        {{ trans('global.extra.add_product') }}
                                                        <i class="fas fa-plus-circle" style="color:lightseagreen"></i>
                                                    </a>
                                                @endcan
                                                @can('receipt_price_view_edit')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.receipt-price-views.edit', $receipt->id) }}">
                                                        {{ trans('global.edit') }}
                                                        <i class="far fa-edit" style="color:cornflowerblue"></i>
                                                    </a>
                                                @endcan
                                                @can('receipt_price_view_print')
                                                    <a class="dropdown-item" target="_blanc"
                                                        href="{{ route('admin.receipt-price-views.print', $receipt->id) }}">
                                                        {{ trans('global.print') }}
                                                        <i class="fas fa-print" style="color:yellowgreen"></i>
                                                    </a>
                                                @endcan
                                                @can('receipt_price_view_duplicate')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.receipt-price-views.duplicate', $receipt->id) }}">
                                                        {{ trans('global.duplicate') }}
                                                        <i class="far fa-clone" style="color:blueviolet"></i>
                                                    </a>
                                                @endcan
                                            @else  
                                                @can('receipt_price_view_restore')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.receipt-price-views.restore', $receipt->id) }}">
                                                        {{ trans('global.restore') }}
                                                        <i class="fas fa-undo" style="color:grey"></i>
                                                    </a>  
                                                @endcan
                                            @endif
                                            @can('receipt_price_view_delete')
                                                <?php $route = route('admin.receipt-price-views.destroy', $receipt->id); ?>
                                                <a class="dropdown-item" href="#"
                                                    onclick="deleteConfirmation('{{ $route }}')">
                                                    {{ trans('global.delete') }}   @isset($deleted) {{ trans('global.permanently') }} @endisset
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
                            <td colspan="10">
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
    @parent 
    <script> 
        function sort_receipt_price_view(el) {
            $('#sort_receipt_price_view').submit();
        }
        
        function add_product(id) {
            $.post('{{ route('admin.receipt-price-views.add_product') }}', {
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
            $.post('{{ route('admin.receipt-price-views.edit_product') }}', {
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
            $.post('{{ route('admin.receipt-price-views.view_products') }}', {
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
