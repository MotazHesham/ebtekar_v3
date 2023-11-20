@extends('layouts.admin')
@section('content')
    <div class="row mb-3">
        @can('receipt_company_create')
            <div class="col-md-3"> 
                <a class="btn btn-success" href="#" data-toggle="modal" data-target="#phoneModal">
                    {{ trans('global.add') }} {{ trans('cruds.receiptCompany.title_singular') }}
                </a>
            </div>
        @endcan

        @if(isset($deleted))
            <div class="col-md-3">
                <a class="btn btn-dark" href="{{ route('admin.receipt-companies.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        @else 
            <div class="col-md-3">
                <a class="btn btn-danger" href="{{ route('admin.receipt-companies.index',['deleted' => 1]) }}">
                    {{ trans('global.extra.deleted_receipts') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Phone Modal -->
    <div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phoneModalLabel">{{ trans('global.add') }}
                        {{ trans('cruds.receiptCompany.title_singular') }}</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.receipt-companies.create') }}">
                        <input type="text" name="phone_number" class="form-control" required
                            placeholder="{{ trans('cruds.receiptCompany.fields.phone_number') }}"
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
                        <b>{{ trans('global.statistics') }} {{ trans('cruds.receiptCompany.title') }}</b>
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
                            <div class="col-md-12 col-sm-12">
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
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="@if(Gate::allows('statistics_receipts')) col-xl-9 @else col-xl-12 @endif col-md-12">
            @include('admin.receiptCompanies.partials.search')
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('global.list') }} {{ trans('cruds.receiptCompany.title') }}
            @isset($deleted)
                {{ trans('global.deleted') }}
            @endisset
        </div>

        <div class="card-body">
            <table
                class="table table-bordered datatable table-responsive-lg table-responsive-md table-responsive-sm">
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
                            {{ trans('cruds.receiptCompany.fields.shipping_address') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.total_cost') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.description') }}
                        </th>
                        <th>
                            {{ trans('global.extra.statuses') }}
                        </th>
                        <th>
                            {{ trans('global.extra.stages') }}
                        </th>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.note') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($receipts as $key => $receipt)
                        <tr data-entry-id="{{ $receipt->id }}" class=" @if($receipt->quickly) quickly @elseif($receipt->no_answer) no_answer @elseif($receipt->done) done @endif">
                            <td>
                                <br>{{ ($key+1) + ($receipts->currentPage() - 1)*$receipts->perPage() }}
                            </td>
                            <td> 
                                <span class="badge rounded-pill text-bg-danger text-white mb-1" style="cursor: pointer"
                                    onclick="show_logs('App\\Models\\ReceiptCompany','{{ $receipt->id }}','receiptCompany')">
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
                                            {{ \App\Models\ReceiptCompany::CLIENT_TYPE_SELECT[$receipt->client_type] }}
                                        </span>
                                    </div>
                                    <div>
                                        <b>{{ $receipt->phone_number ?? '' }}</b>
                                        <br>
                                        <b>{{ $receipt->phone_number_2 ?? '' }}</b>
                                        <br>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge text-bg-primary text-white mb-1">
                                    {{ trans('cruds.receiptCompany.fields.created_at') }}
                                    <br> {{ $receipt->created_at }}
                                </span>
                                @if ($receipt->date_of_receiving_order)
                                    <br>
                                    <span class="badge text-bg-light mb-1">
                                        {{ trans('cruds.receiptCompany.fields.date_of_receiving_order') }}
                                        <br> {{ $receipt->date_of_receiving_order }}
                                    </span>
                                @endif
                                @if ($receipt->send_to_delivery_date)
                                    <br>
                                    <span class="badge text-bg-info text-white mb-1">
                                        {{ trans('cruds.receiptCompany.fields.send_to_delivery_date') }}
                                        <br> {{ $receipt->send_to_delivery_date }}
                                    </span>
                                @endif
                                @if ($receipt->send_to_playlist_date)
                                    <br>
                                    <span class="badge text-bg-dark text-white mb-1">
                                        {{ trans('cruds.receiptCompany.fields.send_to_playlist_date') }}
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
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ trans('cruds.receiptCompany.fields.shipping_country_cost') }}
                                        <br>
                                        {{ dashboard_currency($receipt->shipping_country_cost) }}
                                    </span>
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ trans('cruds.receiptCompany.fields.deposit') }}
                                        <br>
                                        {{ dashboard_currency($receipt->deposit) }}
                                    </span> 
                                </div>
                                <div style="display:flex;justify-content:space-between">
                                    <span class="badge rounded-pill text-bg-light  mb-1">
                                        {{ trans('cruds.receiptCompany.fields.total_cost') }}
                                        <br>
                                        {{ dashboard_currency($receipt->total_cost) }}
                                    </span>
                                    <span class="badge rounded-pill text-bg-success text-white mb-1 total_cost">
                                        = {{ dashboard_currency($receipt->calc_total_for_client()) }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <?php echo $receipt->description;?>
                            </td>
                            <td>
                                <div style="display: flex;justify-content: space-between;">
                                    <div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;"
                                        class="badge text-bg-light mb-1">
                                        <span>
                                            {{ trans('cruds.receiptCompany.fields.quickly') }}
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
                                            {{ trans('cruds.receiptCompany.fields.done') }}
                                        </span>
                                        <br>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input onchange="update_statuses(this,'done')" value="{{ $receipt->id }}"
                                                type="checkbox" class="c-switch-input"
                                                {{ $receipt->done ? 'checked' : null }}>
                                            <span class="c-switch-slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div style="display: flex;justify-content: space-between;">
                                    <div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;"
                                        class="badge text-bg-light mb-1">
                                        <span>
                                            {{ trans('cruds.receiptCompany.fields.calling') }}
                                        </span>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input onchange="update_statuses(this,'calling')" value="{{ $receipt->id }}"
                                                type="checkbox" class="c-switch-input"
                                                {{ $receipt->calling ? 'checked' : null }}>
                                            <span class="c-switch-slider"></span>
                                        </label>
                                    </div>
                                    <div class="badge text-bg-light mb-1" style="margin: 0px 3px;">
                                        <span>
                                            {{ trans('cruds.receiptCompany.fields.no_answer') }}
                                        </span>
                                        <br>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input onchange="update_statuses(this,'no_answer')" value="{{ $receipt->id }}"
                                                type="checkbox" class="c-switch-input"
                                                {{ $receipt->no_answer ? 'checked' : null }}>
                                            <span class="c-switch-slider"></span>
                                        </label>
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
                                @if($receipt->playlist_status == 'pending')
                                    <button class="btn btn-success btn-sm rounded-pill" onclick="playlist_users('{{$receipt->id}}','company')">أرسال للديزاينر</button>
                                @else  
                                    <span onclick="playlist_users('{{$receipt->id}}','company')" style="cursor: pointer"
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
                                @if ($receipt->delivery_man)
                                    <span class="badge text-bg-dark text-white mb-1">
                                        {{ trans('cruds.receiptCompany.fields.delivery_man_id') }}
                                        =>
                                        {{ $receipt->delivery_man->name ?? '' }}
                                    </span>
                                @endif
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

                                            @can('receipt_company_product_access')
                                                <a class="dropdown-item" style="cursor: pointer"
                                                    onclick="view_products('{{ $receipt->id }}')">
                                                    {{ trans('global.extra.view_products') }}
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @if (!isset($deleted)) 
                                                @can('receipt_company_edit')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.receipt-companies.edit', $receipt->id) }}">
                                                        {{ trans('global.edit') }}
                                                        <i class="far fa-edit" style="color:cornflowerblue"></i>
                                                    </a>
                                                @endcan
                                                @can('receipt_company_print')
                                                    <a class="dropdown-item" target="print-frame"
                                                        href="{{ route('admin.receipt-companies.print', $receipt->id) }}">
                                                        {{ trans('global.print') }}
                                                        <i class="fas fa-print" style="color:yellowgreen"></i>
                                                    </a>
                                                @endcan
                                                @can('receipt_company_duplicate')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.receipt-companies.duplicate', $receipt->id) }}">
                                                        {{ trans('global.duplicate') }}
                                                        <i class="far fa-clone" style="color:blueviolet"></i>
                                                    </a>
                                                @endcan 
                                            @else
                                                @can('receipt_company_restore')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.receipt-companies.restore', $receipt->id) }}">
                                                        {{ trans('global.restore') }}
                                                        <i class="fas fa-undo" style="color:grey"></i>
                                                    </a>
                                                @endcan
                                            @endif
                                            @can('receipt_company_delete')
                                                <?php $route = route('admin.receipt-companies.destroy', $receipt->id); ?>
                                                <a class="dropdown-item" href="#"
                                                    onclick="deleteConfirmation('{{ $route }}')">
                                                    {{ trans('global.delete') }} @isset($deleted)
                                                        {{ trans('global.permanently') }}
                                                    @endisset
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
                            <td colspan="9">
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
        function sort_receipt_comapny(el) {
            $('#sort_receipt_comapny').submit();
        }

        
        function update_statuses(el,type){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.receipt-companies.update_statuses') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status, type:type}, function(data){
                if(data == 1){
                    showAlert('success', 'Success', '');
                }else{
                    showAlert('danger', 'Something went wrong', '');
                }
            });
        }
        
    </script>
@endsection
