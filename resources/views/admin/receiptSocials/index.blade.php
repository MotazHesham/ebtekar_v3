@extends('layouts.admin') 
@section('content')

    <div class="row mb-3 text-center">
        @can('receipt_social_create')
            <div class="col-md-3">
                <a class="btn btn-success" href="#" data-toggle="modal" data-target="#phoneModal">
                    {{ __('global.add') }} {{ __('cruds.receiptSocial.title_singular') }}
                </a>
            </div>
        @endcan

        @can('receipt_social_product_access')
            <div class="col-md-3">
                <a class="btn btn-info" href="{{ route('admin.receipt-social-products.index') }}">
                    {{ __('cruds.receiptSocialProduct.title') }}
                </a>
            </div>
        @endcan

        <div class="col-md-2">
            <a class="btn btn-warning" href="#" data-toggle="modal" data-target="#uploadFedexModal">
                {{ __('global.extra.upload_fedex') }}
            </a>
        </div>

        @if(isset($deleted))
            <div class="col-md-2">
                <a class="btn btn-dark" href="{{ route('admin.receipt-socials.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        @else                        
            @if(Gate::allows('soft_delete'))
                <div class="col-md-2">
                    <a class="btn btn-danger" href="{{ route('admin.receipt-socials.index',['deleted' => 1]) }}">
                        {{ __('global.extra.deleted_receipts') }}
                    </a>
                </div>
            @endif
        @endif
        <div class="col-md-2">
            <a class="btn btn-info" href="#" data-toggle="modal" data-target="#productsReportModal">
                تقارير المنتجات
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
                        <div class="col-4 form-group">
                            <label class="control-label" for="start_date">بداية التاريخ</label>
                            <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text"
                                name="start_date" id="start_date" value="{{ $start_date ?? '' }}" required>
                        </div>
                        <div class="col-4 form-group">
                            <label class="control-label" for="end_date">نهاية التاريخ</label>
                            <input class="form-control date {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text"
                                name="end_date" id="end_date" value="{{ $end_date ?? '' }}" required>
                        </div>
                        <div class="col-4 form-group">
                            <label class="control-label" >نوع المنتج</label>
                            <select class="form-control mb-2 @isset($product_type) isset @endisset" name="product_type" id="product_type___">
                                <option value="">نوع المنتج</option>
                                <option value="1">
                                    منتج سيزون
                                </option>
                                <option value="0">
                                    منتج غير سيزون
                                </option>
                            </select> 
                        </div>
                        <div class="col-4 form-group">
                            <label class="control-label" >أختر الموقع</label>
                            <select class="form-control " style="width: 200px" name="website_setting_id" id="website_setting_id__">
                                <option value="">أختر الموقع</option>
                                @foreach ($websites as $id => $entry)
                                    <option value="{{ $id }}" >
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 form-group">
                            <label class="control-label" >ترتيب حسب</label>
                            <select class="form-control mb-2 @isset($sort_by) isset @endisset" name="sort_by" id="sort_by_"> 
                                <option value="quantity">
                                    الكمية المطلوبة
                                </option>
                                <option value="total_cost">
                                    الاجمالي
                                </option>
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
                    <form action="{{ route('admin.receipt-socials.upload_fedex') }}" method="POST" enctype="multipart/form-data">
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

    <!-- Phone Modal -->
    <div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phoneModalLabel">{{ __('global.add') }}
                        {{ __('cruds.receiptSocial.title_singular') }}</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.receipt-socials.create') }}">
                        <div class="row mb-3">
                            @foreach($websites as $id => $entry)
                                <div class="form-check form-check-inline col">
                                    <input class="form-check-input" type="radio" name="website_setting_id" id="{{$id}}" value="{{$id}}" required @if($id == auth()->user()->website_setting_id) checked @endif>
                                    <label class="form-check-label" for="{{$id}}">{{$entry}}</label>
                                </div>
                            @endforeach 
                        </div>
                        <input type="text" name="phone_number" class="form-control" required
                            placeholder="{{ __('cruds.receiptSocial.fields.phone_number') }}"
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
            {{-- <div class="col-xl-3 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <b>{{ __('global.statistics') }} {{ __('cruds.receiptSocial.title') }}</b>
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
                                            <div class="text-medium-emphasis text-uppercase fw-semibold small">مجموع بالعربون
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
                                            <div class="fs-6 fw-semibold text-info">{{ dashboard_currency(round($statistics['total_total_cost_without_deposit'])) }}</div>
                                            <div class="text-medium-emphasis text-uppercase fw-semibold small">مجموع بدون العربون
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
                                            <div class="fs-6 fw-semibold text-danger">{{ dashboard_currency(round($statistics['total_shipping_country_cost'])) }}</div>
                                            <div class="text-medium-emphasis text-uppercase fw-semibold small">اجمالي الشحن
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col-->
                            <div class="col-md-6 col-sm-12">
                                <div class="card">
                                    <div class="card-body p-3 d-flex align-items-center" style="box-shadow: 1px 2px 10px #8080803d;border-radius: 9px;">
                                        <div class="bg-success text-white p-3 me-3">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </div>
                                        <div>
                                            <div class="fs-6 fw-semibold text-success">{{ dashboard_currency(round($statistics['total_grand_total'])) }}</div>
                                            <div class="text-medium-emphasis text-uppercase fw-semibold small">المجموع صافي
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col-->
                        </div>
                        <hr>
                        <p>العملاء الذين اشتروا أكثر من طلب واحد</p>
                        <a href="{{ route('admin.receipt-socials.customer-report') }}" class="btn btn-dark">Excel</a>
                        <button class="btn btn-primary" onclick="showCustomerChart()">رسم بياني</button> 
                        <a href="{{ route('admin.receipt-socials.index',['new_design' => true]) }}" class="btn btn-danger">New Design</a>
                    </div>
                </div>
            </div>  --}}
        @endif

        <!-- Customer Chart Modal -->
        <div class="modal fade" id="customerChartModal" tabindex="-1" aria-labelledby="customerChartModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customerChartModalLabel">توزيع العملاء حسب عدد الطلبات</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <canvas id="customerChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class=" col-md-12">
            @include('admin.receiptSocials.partials.statistics_modern')
            @include('admin.receiptSocials.partials.search_modern')
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ __('global.list') }} {{ __('cruds.receiptSocial.title') }} 
            @isset($deleted)
                {{ __('global.deleted') }}
            @endisset
        </div>
        <div class="card-body">
            <table class="table table-bordered datatable table-responsive-lg table-responsive-md table-responsive-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>
                            {{ __('global.extra.client') }}
                        </th>
                        <th>
                            {{ __('global.extra.dates') }}
                        </th>
                        <th>
                            {{ __('cruds.receiptSocial.fields.shipping_address') }}
                        </th>
                        <th>
                            {{ __('cruds.receiptSocial.fields.total_cost') }}
                        </th>
                        <th>
                            {{ __('global.extra.statuses') }}
                        </th>
                        <th>
                            {{ __('global.extra.stages') }}
                        </th>
                        <th>
                            {{ __('cruds.receiptSocial.fields.note') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receipts as $key => $receipt)
                        <tr data-entry-id="{{ $receipt->id }}" class=" @if($receipt->quickly) quickly @elseif($receipt->returned) returned @elseif($receipt->done) done @endif">
                            <td>
                                <br>{{ ($key+1) + ($receipts->currentPage() - 1)*$receipts->perPage() }}
                                <i class="fas fa-qrcode" onclick="show_qr_code('{{$receipt->order_num}}','s-{{$receipt->id}}')" style="cursor: pointer"></i>
                            </td>
                            <td>
                                <span class="order_num badge rounded-pill
                                            @if($receipt->website_setting_id == 2) order_num_ertgal 
                                            @elseif($receipt->website_setting_id == 3) order_num_figures 
                                            @elseif($receipt->website_setting_id == 4) order_num_shirti 
                                            @elseif($receipt->website_setting_id == 5) order_num_martobia
                                            @elseif($receipt->website_setting_id == 6) order_num_a1_digital
                                            @elseif($receipt->website_setting_id == 7) order_num_ein
                                            @else order_num_ebtekar @endif text-white mb-1" 
                                            onclick="show_logs('App\\Models\\ReceiptSocial','{{ $receipt->id }}','receiptSocial')">
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
                                        <b>{{ $receipt->client_name ?? '' }}</b>
                                        <br>
                                        <span class="badge rounded-pill text-bg-secondary mb-1"
                                            style="border: 1px solid #80808096;">
                                            {{ \App\Models\ReceiptSocial::CLIENT_TYPE_SELECT[$receipt->client_type] }}
                                        </span>
                                    </div>
                                    <div>
                                        <b>{{ $receipt->phone_number ?? '' }}</b> 
                                        <br>
                                        <b>{{ $receipt->phone_number_2 ?? '' }}</b>
                                        
                                        <br>
                                        @if ($receipt->socials)
                                            @foreach ($receipt->socials as $social)
                                                <span class="badge text-bg-info text-white mb-1">
                                                    {{ $social ? $social->name : '' }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                @if($receipt->shopify_id)
                                    <span class="badge rounded-pill text-bg-success text-white">
                                        Shopify Order #{{ $receipt->shopify_order_num }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge text-bg-primary text-white mb-1">
                                    {{ __('cruds.receiptSocial.fields.created_at') }}
                                    <br> {{ $receipt->created_at }}
                                </span>
                                @if($receipt->date_of_receiving_order)
                                    <br>
                                    <span class="badge text-bg-light mb-1">
                                        {{ __('cruds.receiptSocial.fields.date_of_receiving_order') }}
                                        <br> {{ $receipt->date_of_receiving_order }}
                                    </span>
                                @endif
                                @if($receipt->send_to_delivery_date)
                                    <br>
                                    <span class="badge text-bg-info text-white mb-1">
                                        {{ __('cruds.receiptSocial.fields.send_to_delivery_date') }}
                                        <br> {{ $receipt->send_to_delivery_date }}
                                    </span>
                                @endif
                                @if($receipt->send_to_playlist_date)
                                    <br>
                                    <span class="badge text-bg-dark text-white mb-1">
                                        {{ __('cruds.receiptSocial.fields.send_to_playlist_date') }}
                                        <br> {{ $receipt->send_to_playlist_date }}
                                    </span>
                                @endif
                                @if($receipt->done_time)
                                    <br>
                                    <span class="badge text-bg-success text-white mb-1">
                                        {{ __('cruds.receiptSocial.fields.done_time') }}
                                        <br> {{ $receipt->done_time }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill text-bg-warning  mb-1">
                                    {{ $receipt->shipping_country ? $receipt->shipping_country->name : '' }}
                                </span>
                                {{ $receipt->shipping_address ?? '' }}
                            </td>
                            <td> 
                                <div style="display:flex;justify-content:space-between">
                                    @if($receipt->deposit_type )
                                        <span class="badge rounded-pill text-bg-info text-white  mb-1">
                                            {{ __('cruds.receiptSocial.fields.deposit_type') }}
                                            <br>
                                            {{ $receipt->deposit_type ? \App\Models\ReceiptSocial::DEPOSIT_TYPE_SELECT[$receipt->deposit_type] : '' }}
                                        </span>
                                    @endif
                                    @if($receipt->financial_account )
                                        <span class="badge rounded-pill text-bg-info text-white  mb-1">
                                            {{ __('cruds.receiptSocial.fields.financial_account_id') }}
                                            <br>
                                            {{ $receipt->financial_account->account ?? '' }}
                                        </span>
                                    @endif
                                </div>
                                <div style="display:flex;justify-content:space-between">
                                    @if($receipt->deposit > 0)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ __('cruds.receiptSocial.fields.deposit') }}
                                            <br>
                                            {{ dashboard_currency($receipt->deposit) }}
                                        </span>
                                    @endif
                                    @if($receipt->extra_commission > 0)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ __('cruds.receiptSocial.fields.extra_commission') }}
                                            <br>
                                            {{ dashboard_currency($receipt->extra_commission) }}
                                        </span>
                                    @endif
                                    @if($receipt->discounted_amount > 0)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ __('cruds.receiptSocial.fields.discount') }}
                                            <br>
                                            {{ dashboard_currency($receipt->discounted_amount) }}
                                        </span>
                                    @endif
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ __('cruds.receiptSocial.fields.shipping_country_cost') }}
                                        <br>
                                        {{ dashboard_currency($receipt->shipping_country_cost) }}
                                    </span>
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ __('cruds.receiptSocial.fields.total_cost') }}
                                        <br>
                                        {{ dashboard_currency($receipt->total_cost) }}
                                    </span>
                                </div>
                                <div style="display:flex;justify-content:space-between">
                                    <span class="badge rounded-pill text-bg-success text-white mb-1 total_cost">
                                        = {{ dashboard_currency($receipt->calc_total_for_client()) }}
                                    </span>
                                    <div class="badge text-bg-light mb-1" style="margin: 0px 3px;">
                                        <span>
                                            {{ __('cruds.receiptSocial.fields.supplied') }}
                                        </span>
                                        <br>
                                        <div id="supplied-{{$receipt->id}}">
                                            @if($receipt->supplied)
                                                <i class="far fa-check-circle" style="padding: 5px; font-size: 20px; color: green;"></i>
                                            @else
                                                @if(Gate::allows('supplied'))
                                                    <label class="c-switch c-switch-pill c-switch-success">
                                                        <input onchange="update_statuses(this,'supplied')" value="{{ $receipt->id }}"
                                                            type="checkbox" class="c-switch-input"
                                                            {{ $receipt->supplied ? 'checked' : null }}>
                                                        <span class="c-switch-slider"></span>
                                                    </label>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex;justify-content: space-between;">
                                    <div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;"
                                        class="badge text-bg-light mb-1">
                                        <span>
                                            {{ __('cruds.receiptSocial.fields.quickly') }}
                                        </span>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input onchange="update_statuses(this,'quickly')" value="{{ $receipt->id }}"
                                                type="checkbox" class="c-switch-input"
                                                {{ $receipt->quickly ? 'checked' : null }}>
                                            <span class="c-switch-slider"></span>
                                        </label>
                                    </div>
                                    <div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;"
                                        class="badge text-bg-light mb-1">
                                        <span>
                                            {{ __('cruds.receiptSocial.fields.confirm') }}
                                        </span>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input onchange="update_statuses(this,'confirm')" value="{{ $receipt->id }}"
                                                type="checkbox" class="c-switch-input"
                                                {{ $receipt->confirm ? 'checked' : null }}>
                                            <span class="c-switch-slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div style="display: flex;justify-content: space-between;">
                                    <div class="badge text-bg-light mb-1" style="margin: 0px 3px;">
                                        <span>
                                            {{ __('cruds.receiptSocial.fields.returned') }}
                                        </span>
                                        <br>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input onchange="update_statuses(this,'returned')" value="{{ $receipt->id }}"
                                                type="checkbox" class="c-switch-input"
                                                {{ $receipt->returned ? 'checked' : null }}>
                                            <span class="c-switch-slider"></span>
                                        </label>
                                    </div>
                                    <div class="badge text-bg-light mb-1" style="margin: 0px 3px;">
                                        <span>
                                            {{ __('cruds.receiptSocial.fields.done') }}
                                        </span>
                                        <br>
                                        
                                        @if(Gate::allows('done'))
                                            <label class="c-switch c-switch-pill c-switch-success">
                                                <input  onchange="update_statuses(this,'done')" value="{{ $receipt->id }}"
                                                    type="checkbox" class="c-switch-input"
                                                    {{ $receipt->done ? 'checked' : null }}>
                                                <span class="c-switch-slider"></span>
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="badge text-bg-{{ __('global.delivery_status.colors.' . $receipt->delivery_status) }} mb-1">
                                    {{ $receipt->delivery_status ? __('global.delivery_status.status.' . $receipt->delivery_status) : '' }}
                                </span>
                                <span
                                    class="badge text-bg-{{ __('global.payment_status.colors.' . $receipt->payment_status) }} mb-1">
                                    {{ $receipt->payment_status ? __('global.payment_status.status.' . $receipt->payment_status) : '' }}
                                </span>
                                <br> 
                                @can('hold')
                                    <form action="{{ route('admin.receipt-socials.update_statuses') }}" method="POST" style="display: inline" id="hold-form-{{ $receipt->id }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $receipt->id }}">
                                        <input type="hidden" name="type" value="hold">
                                        <input type="hidden" name="hold_reason" id="hold-reason-{{ $receipt->id }}" value="{{ $receipt->hold_reason }}">
                                        @if($receipt->hold == 0)
                                            <input type="hidden" name="status" value="1">
                                            <button type="button" class="btn btn-dark btn-sm rounded-pill" onclick="showHoldModal('{{ $receipt->id }}','{{ $receipt->hold_reason }}')">Hold</button>
                                        @else 
                                            <input type="hidden" name="status" value="0">
                                            <button type="submit" class="btn btn-warning btn-sm rounded-pill">UnHold</button> 
                                            @if($receipt->hold_reason)
                                                <span class="badge bg-info text-white" style="cursor: pointer" 
                                                    data-toggle="tooltip" 
                                                    data-placement="top" 
                                                    title="{{ $receipt->hold_reason }}">
                                                    <i class="fas fa-info-circle"></i> Hold Reason
                                                </span>
                                            @endif
                                        @endif
                                    </form>
                                @endcan
                                @if($receipt->playlist_status == 'pending')
                                    @if($receipt->receipts_receipt_social_products_count  > 0) 
                                        <button class="btn btn-success btn-sm rounded-pill" onclick="playlist_users('{{$receipt->id}}','social')">أرسال لمراحل التشغيل</button>  
                                    @endif
                                @else  
                                    <span onclick="playlist_users('{{$receipt->id}}','social')" 
                                        class="playlist_status badge text-bg-{{ __('global.playlist_status.colors.' . $receipt->playlist_status) }} mb-1">
                                        {{ $receipt->playlist_status ? __('global.playlist_status.status.' . $receipt->playlist_status) : '' }}
                                    </span>
                                @endif
                                <hr>
                                <span class="badge text-bg-danger text-white mb-1">
                                    {{ __('global.extra.created_by') }}
                                    =>
                                    {{ $receipt->staff->name ?? '' }}
                                </span>
                                @if($receipt->delivery_man)
                                    <span class="badge text-bg-dark text-white mb-1">
                                        {{ __('cruds.receiptSocial.fields.delivery_man_id') }}
                                        =>
                                        {{ $receipt->delivery_man->name ?? '' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{ $receipt->note ?? '' }}
                            </td>
                            <td style="margin: 11px; padding: 50px 15px;">
                                <div class="c-header" style="background: #ffffff00;height: fit-content;min-height:20px;border-bottom:0px">
                                    <div class="dropdown text-center">
                                        <a style="cursor: pointer" class="dropdown-button" id="dropdown-menu-{{ $receipt->id }}" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                                            <span>
                                                <i class="far fa-edit" style="font-size:28px;color:black"></i>
                                                أجراءات
                                            </span>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdown-menu-{{ $receipt->id }}">
                                                @can('receipt_social_view_products')
                                                    <a class="dropdown-item" style="cursor: pointer"
                                                        onclick="view_products('{{ $receipt->id }}')">
                                                        {{ __('global.extra.view_products') }}
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan
                                            <a class="dropdown-item" style="cursor: pointer"
                                                onclick="open_followups('{{ $receipt->id }}')">
                                                {{ __('global.followups') }}
                                                <span style="position:relative; display:inline-block; margin-left:4px;">
                                                    <i class="far fa-comments" style="color:darkslateblue"></i>
                                                    @if($receipt->followups_count > 0)
                                                        <span class="badge"
                                                            style="position:absolute; top:-6px; right:-8px; background:#b31262; color:#fff; border-radius:10px; font-size:10px; line-height:1; padding:2px 5px;">
                                                            {{ $receipt->followups_count }}
                                                        </span>
                                                    @endif
                                                </span>
                                            </a>
                                            @if(!$receipt->hold || auth()->user()->is_admin)
                                                @if(!isset($deleted))
                                                    @can('receipt_social_add_product')
                                                        <a class="dropdown-item" style="cursor: pointer"
                                                            onclick="add_product('{{ $receipt->id }}')">
                                                            {{ __('global.extra.add_product') }}
                                                            <i class="fas fa-plus-circle" style="color:lightseagreen"></i>
                                                        </a>
                                                    @endcan 
                                                    @can('receipt_social_edit')
                                                        <a class="dropdown-item" 
                                                            href="{{ route('admin.receipt-socials.edit', $receipt->id) }}">
                                                            {{ __('global.edit') }}
                                                            <i class="far fa-edit" style="color:cornflowerblue"></i>
                                                        </a>
                                                    @endcan
                                                    @can('receipt_social_print')
                                                        <a class="dropdown-item" target="print-frame"
                                                            href="{{ route('admin.receipt-socials.print', $receipt->id) }}">
                                                            {{ __('global.print') }}
                                                            <i class="fas fa-print" style="color:yellowgreen"></i>
                                                        </a>
                                                    @endcan
                                                    @can('receipt_social_duplicate')
                                                        <a class="dropdown-item" onclick="return confirm('Are you sure you want to duplicate this receipt?')"
                                                            href="{{ route('admin.receipt-socials.duplicate', $receipt->id) }}">
                                                            {{ __('global.duplicate') }}
                                                            <i class="far fa-clone" style="color:blueviolet"></i>
                                                        </a>
                                                    @endcan
                                                    @can('receipt_social_receive_money')
                                                        <a class="dropdown-item" target="print-frame"
                                                            href="{{ route('admin.receipt-socials.receive_money', $receipt->id) }}">
                                                            {{ __('global.receive_money') }}
                                                            <i class="fas fa-money-bill-wave" style="color:cadetblue"></i>
                                                        </a>
                                                    @endcan
                                                @else  
                                                    @can('receipt_social_restore')
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.receipt-socials.restore', $receipt->id) }}">
                                                            {{ __('global.restore') }}
                                                            <i class="fas fa-undo" style="color:grey"></i>
                                                        </a>  
                                                    @endcan
                                                @endif
                                                @can('receipt_social_delete')
                                                    <?php $route = route('admin.receipt-socials.destroy', $receipt->id); ?>
                                                    <a class="dropdown-item"
                                                        href="#" onclick="deleteConfirmation('{{$route}}')">
                                                        {{ __('global.delete') }} @isset($deleted) {{ __('global.permanently') }} @endisset
                                                        <i class="fas fa-trash-alt" style="color:darkred"></i>
                                                    </a>
                                                @endcan
                                            @endif
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

    <!-- Followups Modal -->
    <div class="modal fade" id="followups-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('global.followups') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <!-- End Followups Modal -->

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
    <script>
        $(document).ready(function() {  
            $('input[type=checkbox]').on('click',function() {     
                return confirm('are you sure?');
            });
        });

        function products_report(){
            $.post('{{ route('admin.receipt-social-products.products_report') }}', {
                _token: '{{ csrf_token() }}',
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                website_setting_id: $('#website_setting_id__').val(),
                product_type: $('#product_type___').val(),
                sort_by: $('#sort_by_').val(),
            }, function(data) {
                $('#products-report-div').html(null); 
                $('#products-report-div').html(data); 
            });
        }

        function show_qr_code(order_num,bar_code){
            $.post('{{ route('admin.show_qr_code') }}', {
                _token: '{{ csrf_token() }}',
                order_num: order_num,
                bar_code: bar_code,
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data); 
            });
        }
    </script>
    <script>
        function open_followups(receipt_id){
            $('#followups-modal').modal('show');
            load_followups(receipt_id);
        }
        function load_followups(receipt_id){
            $.post({
                url: '{{ route('admin.receipt-social-followups.index') }}',
                data: {receipt_social_id: receipt_id},
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function(html){
                    $('#followups-modal .modal-body').html(html);
                }
            });
        }
    </script>
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
        function SimpleUploadAdapter(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                return {
                    upload: function() {
                        return loader.file
                            .then(function(file) {
                                return new Promise(function(resolve, reject) {
                                    // Init request
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST',
                                        '{{ route('admin.receipt-social-products.storeCKEditorImages') }}',
                                        true);
                                    xhr.setRequestHeader('x-csrf-token', window._token);
                                    xhr.setRequestHeader('Accept', 'application/json');
                                    xhr.responseType = 'json';

                                    // Init listeners
                                    var genericErrorText =
                                        `Couldn't upload file: ${ file.name }.`;
                                    xhr.addEventListener('error', function() {
                                        reject(genericErrorText)
                                    });
                                    xhr.addEventListener('abort', function() {
                                        reject()
                                    });
                                    xhr.addEventListener('load', function() {
                                        var response = xhr.response;

                                        if (!response || xhr.status !== 201) {
                                            return reject(response && response
                                                .message ?
                                                `${genericErrorText}\n${xhr.status} ${response.message}` :
                                                `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`
                                            );
                                        }

                                        $('form').append(
                                            '<input type="hidden" name="ck-media[]" value="' +
                                            response.id + '">');

                                        resolve({
                                            default: response.url
                                        });
                                    });

                                    if (xhr.upload) {
                                        xhr.upload.addEventListener('progress', function(
                                            e) {
                                            if (e.lengthComputable) {
                                                loader.uploadTotal = e.total;
                                                loader.uploaded = e.loaded;
                                            }
                                        });
                                    }

                                    // Send request
                                    var data = new FormData();
                                    data.append('upload', file);
                                    data.append('crud_id', '{{ $product->id ?? 0 }}');
                                    xhr.send(data);
                                });
                            })
                    }
                };
            }
        }
    </script>
    <script>

        $(document).ready(function() {
            @if(session('store_receipt_socail_id') && session('store_receipt_socail_id') != null)
                add_product('{{session("store_receipt_socail_id")}}') 
            @endif
            @if(session('update_receipt_socail_id') && session('update_receipt_socail_id') != null)
                view_products('{{session("update_receipt_socail_id")}}') 
            @endif
        });

        function sort_receipt_social(el) {
            $('#sort_receipt_social').submit();
        }

        
        function update_statuses(el,type){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.receipt-socials.update_statuses') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status, type:type}, function(data){
                if (data['status'] == '1') {
                    showAlert('success', 'Success', data['message']);
                } else if(data['status'] == '2') { 
                    $('#supplied-'+el.value).html(data['first']);
                    showAlert('success', 'Success', data['message']);
                } 
            });
        }

        function add_product(id) {
            $.post('{{ route('admin.receipt-socials.add_product') }}', {
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
            $.post('{{ route('admin.receipt-socials.edit_product') }}', {
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
            $.post('{{ route('admin.receipt-socials.view_products') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);
            });
        }

        var photo_id = 2;

        function add_more_slider_image() {
            var photoAdd = '<div class="row">';
            photoAdd += '<div class="col-md-2">';
            photoAdd +=
                '<button type="button" onclick="delete_this_row(this)" class="btn btn-danger">{{ __('global.extra.delete_photo') }}</button>';
            photoAdd += '</div>';
            photoAdd += '<div class="col-md-6">';
            photoAdd += '<input type="file" name="photos[][photo]" id="photos-' + photo_id +
                '" class="custom-input-file custom-input-file--4 form-control" data-multiple-caption="{count} files selected" multiple accept="image/*" />';
            photoAdd += '<label for="photos-' + photo_id + '" class="mw-100 mb-3">';
            photoAdd += '<span></span>';
            photoAdd += '</label>';
            photoAdd += '</div>';
            photoAdd += '<div class="col-md-4">';
            photoAdd +=
                '<input type="text" name="photos[][note]" class="form-control" placeholder="{{ __('global.extra.photo_note') }}">';
            photoAdd += '</div>';
            photoAdd += '</div>';
            $('#product-images').append(photoAdd);

            photo_id++;
            imageInputInitialize();
        }

        function delete_this_row(em) {
            $(em).closest('.row').remove();
        }

        function imageInputInitialize() {
            $('.custom-input-file').each(function() {
                var $input = $(this),
                    $label = $input.next('label'),
                    labelVal = $label.html();

                $input.on('change', function(e) {
                    var fileName = '';

                    if (this.files && this.files.length > 1)
                        fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}',
                            this.files.length);
                    else if (e.target.value)
                        fileName = e.target.value.split('\\').pop();

                    if (fileName)
                        $label.find('span').html(fileName);
                    else
                        $label.html(labelVal);
                });

                // Firefox bug fix
                $input
                    .on('focus', function() {
                        $input.addClass('has-focus');
                    })
                    .on('blur', function() {
                        $input.removeClass('has-focus');
                    });
            });
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let customerChart = null; // Declare chart variable in global scope

        function showCustomerChart() {
            $('#customerChartModal').modal('show');
            
            $.ajax({
                url: '{{ route("admin.receipt-socials.customer-chart") }}',
                type: 'GET',
                success: function(data) {
                    const ctx = document.getElementById('customerChart').getContext('2d');
                    
                    // Destroy existing chart if it exists
                    if (customerChart instanceof Chart) {
                        customerChart.destroy();
                    }
                    
                    customerChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'عدد العملاء',
                                data: data.values,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.5)',
                                    'rgba(54, 162, 235, 0.5)',
                                    'rgba(255, 206, 86, 0.5)',
                                    'rgba(75, 192, 192, 0.5)',
                                    'rgba(153, 102, 255, 0.5)',
                                    'rgba(255, 159, 64, 0.5)',
                                    'rgba(199, 199, 199, 0.5)',
                                    'rgba(83, 102, 255, 0.5)',
                                    'rgba(40, 159, 64, 0.5)',
                                    'rgba(210, 199, 199, 0.5)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                    'rgba(199, 199, 199, 1)',
                                    'rgba(83, 102, 255, 1)',
                                    'rgba(40, 159, 64, 1)',
                                    'rgba(210, 199, 199, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'توزيع العملاء حسب عدد الطلبات'
                                },
                                legend: {
                                    position: 'right',
                                    labels: {
                                        font: {
                                            size: 14
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = Math.round((value / total) * 100);
                                            return `${label}: ${value} عميل (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
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
