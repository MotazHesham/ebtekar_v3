@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    {{ __('global.show') }} {{ __('cruds.rBranch.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.r-branches.index') }}">
                                {{ __('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ __('cruds.rBranch.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $rBranch->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.rBranch.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $rBranch->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.rBranch.fields.phone_number') }}
                                    </th>
                                    <td>
                                        {{ $rBranch->phone_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.rBranch.fields.remaining') }}
                                    </th>
                                    <td>
                                        {{ $rBranch->remaining }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.rBranch.fields.payment_type') }}
                                    </th>
                                    <td>
                                        {{ App\Models\RBranch::PAYMENT_TYPE_SELECT[$rBranch->payment_type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('cruds.rBranch.fields.r_client') }}
                                    </th>
                                    <td>
                                        {{ $rBranch->r_client->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.r-branches.index') }}">
                                {{ __('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8"> 
            <div class="card"> 
                <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                    @if($rBranch->payment_type == 'parts' && $rBranch->r_client->manage_type == 'seperate')
                        <li class="nav-item">
                            <a class="nav-link active" href="#r_branche_incomes" role="tab" data-toggle="tab">
                                الدفعات
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link @if($rBranch->payment_type != 'parts') active @endif" href="#qr_products" role="tab" data-toggle="tab">
                            منتجات Qr
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#r_branche_qr_products" role="tab" data-toggle="tab">
                            منتجات الفرع
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#r_branche_qr_history" role="tab" data-toggle="tab">
                            QrScan History
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    @if($rBranch->payment_type == 'parts' && $rBranch->r_client->manage_type == 'seperate')
                        <div class="tab-pane active" role="tabpanel" id="r_branche_incomes">
                            @includeIf('admin.rBranches.relationships.incomes', [
                                'incomes' => $rBranch->incomes,
                            ])
                        </div>
                    @endif
                    <div class="tab-pane @if($rBranch->payment_type != 'parts') active @endif" role="tabpanel" id="qr_products">
                        @includeIf('admin.rBranches.relationships.qr_products', [
                            'qr_products' => $qr_products,
                        ])
                    </div>
                    <div class="tab-pane" role="tabpanel" id="r_branche_qr_products">
                        @includeIf('admin.rBranches.relationships.qr_products_rbranch', [
                            'qr_products_rbranch' => $rBranch->qr_products_rbranch,
                        ])
                    </div>
                    <div class="tab-pane" role="tabpanel" id="r_branche_qr_history">
                        @includeIf('admin.rBranches.relationships.qr_scan_history', [
                            'qr_scan_history' => $rBranch->qr_scan_history,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
