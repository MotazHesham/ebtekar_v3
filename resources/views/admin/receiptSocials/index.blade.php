@extends('layouts.admin') 
@section('content')

    <div class="row mb-3 text-center">
        @can('receipt_social_create')
            <div class="col-md-3">
                <a class="btn btn-success" href="#" data-toggle="modal" data-target="#phoneModal">
                    {{ trans('global.add') }} {{ trans('cruds.receiptSocial.title_singular') }}
                </a>
            </div>
        @endcan

        @can('receipt_social_product_access')
            <div class="col-md-3">
                <a class="btn btn-info" href="{{ route('admin.receipt-social-products.index') }}">
                    {{ trans('cruds.receiptSocialProduct.title') }}
                </a>
            </div>
        @endcan

        <div class="col-md-3">
            <a class="btn btn-warning" href="#" data-toggle="modal" data-target="#uploadFedexModal">
                {{ trans('global.extra.upload_fedex') }}
            </a>
        </div>

        @if(isset($deleted))
            <div class="col-md-3">
                <a class="btn btn-dark" href="{{ route('admin.receipt-socials.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        @else                        
            @if(Gate::allows('soft_delete'))
                <div class="col-md-3">
                    <a class="btn btn-danger" href="{{ route('admin.receipt-socials.index',['deleted' => 1]) }}">
                        {{ trans('global.extra.deleted_receipts') }}
                    </a>
                </div>
            @endif
        @endif
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
                            <label class="required" for="uploaded_file">{{ trans('cruds.excelFile.fields.uploaded_file') }}</label>
                            <div class="needsclick dropzone {{ $errors->has('uploaded_file') ? 'is-invalid' : '' }}" id="uploaded_file-dropzone">
                            </div>
                            @if($errors->has('uploaded_file'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('uploaded_file') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.excelFile.fields.uploaded_file_helper') }}</span>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-success">{{ trans('global.continue') }}</button>
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
                    <h5 class="modal-title" id="phoneModalLabel">{{ trans('global.add') }}
                        {{ trans('cruds.receiptSocial.title_singular') }}</h5>
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
                            placeholder="{{ trans('cruds.receiptSocial.fields.phone_number') }}"
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
                        <b>{{ trans('global.statistics') }} {{ trans('cruds.receiptSocial.title') }}</b>
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
        @endif
        <div class="@if(Gate::allows('statistics_receipts')) col-xl-9 @else col-xl-12 @endif col-md-12">
            @include('admin.receiptSocials.partials.search')
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('global.list') }} {{ trans('cruds.receiptSocial.title') }} 
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
                            {{ trans('cruds.receiptSocial.fields.shipping_address') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.total_cost') }}
                        </th>
                        <th>
                            {{ trans('global.extra.statuses') }}
                        </th>
                        <th>
                            {{ trans('global.extra.stages') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.note') }}
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
                            </td>
                            <td>
                                <span class="badge text-bg-primary text-white mb-1">
                                    {{ trans('cruds.receiptSocial.fields.created_at') }}
                                    <br> {{ $receipt->created_at }}
                                </span>
                                @if($receipt->date_of_receiving_order)
                                    <br>
                                    <span class="badge text-bg-light mb-1">
                                        {{ trans('cruds.receiptSocial.fields.date_of_receiving_order') }}
                                        <br> {{ $receipt->date_of_receiving_order }}
                                    </span>
                                @endif
                                @if($receipt->send_to_delivery_date)
                                    <br>
                                    <span class="badge text-bg-info text-white mb-1">
                                        {{ trans('cruds.receiptSocial.fields.send_to_delivery_date') }}
                                        <br> {{ $receipt->send_to_delivery_date }}
                                    </span>
                                @endif
                                @if($receipt->send_to_playlist_date)
                                    <br>
                                    <span class="badge text-bg-dark text-white mb-1">
                                        {{ trans('cruds.receiptSocial.fields.send_to_playlist_date') }}
                                        <br> {{ $receipt->send_to_playlist_date }}
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
                                <div style="display:flex;justify-content:space-between">
                                    @if($receipt->deposit > 0)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ trans('cruds.receiptSocial.fields.deposit') }}
                                            <br>
                                            {{ dashboard_currency($receipt->deposit) }}
                                        </span>
                                    @endif
                                    @if($receipt->extra_commission > 0)
                                        <span class="badge rounded-pill text-bg-light  mb-1">
                                            {{ trans('cruds.receiptSocial.fields.extra_commission') }}
                                            <br>
                                            {{ dashboard_currency($receipt->extra_commission) }}
                                        </span>
                                    @endif
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ trans('cruds.receiptSocial.fields.shipping_country_cost') }}
                                        <br>
                                        {{ dashboard_currency($receipt->shipping_country_cost) }}
                                    </span>
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ trans('cruds.receiptSocial.fields.total_cost') }}
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
                                            {{ trans('cruds.receiptSocial.fields.supplied') }}
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
                                            {{ trans('cruds.receiptSocial.fields.quickly') }}
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
                                            {{ trans('cruds.receiptSocial.fields.confirm') }}
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
                                            {{ trans('cruds.receiptSocial.fields.returned') }}
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
                                            {{ trans('cruds.receiptSocial.fields.done') }}
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
                                    class="badge text-bg-{{ trans('global.delivery_status.colors.' . $receipt->delivery_status) }} mb-1">
                                    {{ $receipt->delivery_status ? trans('global.delivery_status.status.' . $receipt->delivery_status) : '' }}
                                </span>
                                <span
                                    class="badge text-bg-{{ trans('global.payment_status.colors.' . $receipt->payment_status) }} mb-1">
                                    {{ $receipt->payment_status ? trans('global.payment_status.status.' . $receipt->payment_status) : '' }}
                                </span>
                                <br> 
                                @can('hold')
                                    <form action="{{ route('admin.receipt-socials.update_statuses') }}" method="POST" style="display: inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $receipt->id }}">
                                        <input type="hidden" name="type" value="hold">
                                        @if($receipt->hold == 0)
                                            <input type="hidden" name="status" value="1">
                                            <button type="submit" class="btn btn-dark btn-sm rounded-pill">Hold </button>
                                        @else 
                                            <input type="hidden" name="status" value="0">
                                            <button type="submit" class="btn btn-warning btn-sm rounded-pill">UnHold </button> 
                                        @endif
                                    </form>
                                @endcan
                                @if($receipt->playlist_status == 'pending')
                                    @if($receipt->receipts_receipt_social_products_count  > 0)
                                        <button class="btn btn-success btn-sm rounded-pill" onclick="playlist_users('{{$receipt->id}}','social')">أرسال للديزاينر</button>
                                    @endif
                                @else  
                                    <span onclick="playlist_users('{{$receipt->id}}','social')" 
                                        class="playlist_status badge text-bg-{{ trans('global.playlist_status.colors.' . $receipt->playlist_status) }} mb-1">
                                        {{ $receipt->playlist_status ? trans('global.playlist_status.status.' . $receipt->playlist_status) : '' }}
                                    </span>
                                @endif
                                <hr>
                                <span class="badge text-bg-danger text-white mb-1">
                                    {{ trans('global.extra.created_by') }}
                                    =>
                                    {{ $receipt->staff->name ?? '' }}
                                </span>
                                @if($receipt->delivery_man)
                                    <span class="badge text-bg-dark text-white mb-1">
                                        {{ trans('cruds.receiptSocial.fields.delivery_man_id') }}
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
                                                @can('receipt_social_product_access')
                                                    <a class="dropdown-item" style="cursor: pointer"
                                                        onclick="view_products('{{ $receipt->id }}')">
                                                        {{ trans('global.extra.view_products') }}
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan
                                            @if(!$receipt->hold || auth()->user()->is_admin)
                                                @if(!isset($deleted))
                                                    @can('receipt_social_product_create')
                                                        <a class="dropdown-item" style="cursor: pointer"
                                                            onclick="add_product('{{ $receipt->id }}')">
                                                            {{ trans('global.extra.add_product') }}
                                                            <i class="fas fa-plus-circle" style="color:lightseagreen"></i>
                                                        </a>
                                                    @endcan
                                                    @can('receipt_social_edit')
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.receipt-socials.edit', $receipt->id) }}">
                                                            {{ trans('global.edit') }}
                                                            <i class="far fa-edit" style="color:cornflowerblue"></i>
                                                        </a>
                                                    @endcan
                                                    @can('receipt_social_print')
                                                        <a class="dropdown-item" target="print-frame"
                                                            href="{{ route('admin.receipt-socials.print', $receipt->id) }}">
                                                            {{ trans('global.print') }}
                                                            <i class="fas fa-print" style="color:yellowgreen"></i>
                                                        </a>
                                                    @endcan
                                                    @can('receipt_social_duplicate')
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.receipt-socials.duplicate', $receipt->id) }}">
                                                            {{ trans('global.duplicate') }}
                                                            <i class="far fa-clone" style="color:blueviolet"></i>
                                                        </a>
                                                    @endcan
                                                    @can('receipt_social_receive_money')
                                                        <a class="dropdown-item" target="print-frame"
                                                            href="{{ route('admin.receipt-socials.receive_money', $receipt->id) }}">
                                                            {{ trans('global.receive_money') }}
                                                            <i class="fas fa-money-bill-wave" style="color:cadetblue"></i>
                                                        </a>
                                                    @endcan
                                                @else  
                                                    @can('receipt_social_restore')
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.receipt-socials.restore', $receipt->id) }}">
                                                            {{ trans('global.restore') }}
                                                            <i class="fas fa-undo" style="color:grey"></i>
                                                        </a>  
                                                    @endcan
                                                @endif
                                                @can('receipt_social_delete')
                                                    <?php $route = route('admin.receipt-socials.destroy', $receipt->id); ?>
                                                    <a class="dropdown-item"
                                                        href="#" onclick="deleteConfirmation('{{$route}}')">
                                                        {{ trans('global.delete') }} @isset($deleted) {{ trans('global.permanently') }} @endisset
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
@endsection

@section('scripts') 
    <script>
        $(document).ready(function() {  
            $('input[type=checkbox]').on('click',function() {     
                return confirm('are you sure?');
            });
        });

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
            @if(session('store_receipt_id') && session('store_receipt_id') != null)
                add_product('{{session("store_receipt_id")}}') 
            @endif
            @if(session('update_receipt_id') && session('update_receipt_id') != null)
                view_products('{{session("update_receipt_id")}}') 
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
                '<button type="button" onclick="delete_this_row(this)" class="btn btn-danger">{{ trans('global.extra.delete_photo') }}</button>';
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
                '<input type="text" name="photos[][note]" class="form-control" placeholder="{{ trans('global.extra.photo_note') }}">';
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
@endsection
