@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.receiptOutgoing.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-outgoings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoing.fields.id') }}
                        </th>
                        <td>
                            {{ $receiptOutgoing->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoing.fields.order_num') }}
                        </th>
                        <td>
                            {{ $receiptOutgoing->order_num }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoing.fields.date_of_receiving_order') }}
                        </th>
                        <td>
                            {{ $receiptOutgoing->date_of_receiving_order }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoing.fields.client_name') }}
                        </th>
                        <td>
                            {{ $receiptOutgoing->client_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoing.fields.phone_number') }}
                        </th>
                        <td>
                            {{ $receiptOutgoing->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoing.fields.total_cost') }}
                        </th>
                        <td>
                            {{ $receiptOutgoing->total_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoing.fields.note') }}
                        </th>
                        <td>
                            {{ $receiptOutgoing->note }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoing.fields.done') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptOutgoing->done ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoing.fields.printing_times') }}
                        </th>
                        <td>
                            {{ $receiptOutgoing->printing_times }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoing.fields.staff') }}
                        </th>
                        <td>
                            {{ $receiptOutgoing->staff->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-outgoings.index') }}">
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
            <a class="nav-link" href="#receipt_outgoing_receipt_outgoing_products" role="tab" data-toggle="tab">
                {{ trans('cruds.receiptOutgoingProduct.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="receipt_outgoing_receipt_outgoing_products">
            @includeIf('admin.receiptOutgoings.relationships.receiptOutgoingReceiptOutgoingProducts', ['receiptOutgoingProducts' => $receiptOutgoing->receiptOutgoingReceiptOutgoingProducts])
        </div>
    </div>
</div>

@endsection