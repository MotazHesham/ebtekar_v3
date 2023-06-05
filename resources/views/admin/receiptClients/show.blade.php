@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.receiptClient.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-clients.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.id') }}
                        </th>
                        <td>
                            {{ $receiptClient->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.date_of_receiving_order') }}
                        </th>
                        <td>
                            {{ $receiptClient->date_of_receiving_order }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.order_num') }}
                        </th>
                        <td>
                            {{ $receiptClient->order_num }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.client_name') }}
                        </th>
                        <td>
                            {{ $receiptClient->client_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.phone_number') }}
                        </th>
                        <td>
                            {{ $receiptClient->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.deposit') }}
                        </th>
                        <td>
                            {{ $receiptClient->deposit }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.discount') }}
                        </th>
                        <td>
                            {{ $receiptClient->discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.note') }}
                        </th>
                        <td>
                            {{ $receiptClient->note }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.total_cost') }}
                        </th>
                        <td>
                            {{ $receiptClient->total_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.done') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptClient->done ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.quickly') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptClient->quickly ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.printing_times') }}
                        </th>
                        <td>
                            {{ $receiptClient->printing_times }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptClient.fields.staff') }}
                        </th>
                        <td>
                            {{ $receiptClient->staff->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-clients.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#receipts_receipt_client_products" role="tab" data-toggle="tab">
                {{ trans('cruds.receiptClientProduct.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="receipts_receipt_client_products">
            @includeIf('admin.receiptClients.relationships.receiptsReceiptClientProducts', ['receiptClientProducts' => $receiptClient->receiptsReceiptClientProducts])
        </div>
    </div>
</div>

@endsection