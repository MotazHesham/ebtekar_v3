@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.egyptexpressAirwayBill.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.egyptexpress-airway-bills.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.id') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.shipper_reference') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->shipper_reference }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.order_num') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->order_num }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.airway_bill_number') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->airway_bill_number ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.tracking_number') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->tracking_number ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.status') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->status ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.status_description') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->status_description ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.receiver_name') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->receiver_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.receiver_phone') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->receiver_phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.receiver_city') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->receiver_city }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.destination') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->destination }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.number_of_pieces') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->number_of_pieces }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.weight') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->weight }} kg
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.goods_description') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->goods_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.cod_amount') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->cod_amount }} {{ $egyptexpressAirwayBill->cod_currency }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.invoice_value') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->invoice_value }} {{ $egyptexpressAirwayBill->invoice_currency }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.is_successful') }}
                        </th>
                        <td>
                            @if($egyptexpressAirwayBill->is_successful)
                                <span class="badge bg-success">Success</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.error_message') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->error_message ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.model_type') }}
                        </th>
                        <td>
                            {{ class_basename($egyptexpressAirwayBill->model_type) }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.model_id') }}
                        </th>
                        <td>
                            @if($egyptexpressAirwayBill->model)
                                @php
                                    $modelType = $egyptexpressAirwayBill->model_type;
                                    $modelId = $egyptexpressAirwayBill->model_id;
                                    $routeName = '';
                                    if (str_contains($modelType, 'ReceiptSocial')) {
                                        $routeName = 'admin.receipt-socials.show';
                                    } elseif (str_contains($modelType, 'ReceiptCompany')) {
                                        $routeName = 'admin.receipt-companies.show';
                                    } elseif (str_contains($modelType, 'Order')) {
                                        $routeName = 'admin.orders.show';
                                    }
                                @endphp
                                @if($routeName)
                                    <a href="{{ route($routeName, $modelId) }}" target="_blank">
                                        {{ $modelId }}
                                    </a>
                                @else
                                    {{ $modelId }}
                                @endif
                            @else
                                {{ $egyptexpressAirwayBill->model_id }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.http_status_code') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->http_status_code ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.request_payload') }}
                        </th>
                        <td>
                            <pre style="max-height: 300px; overflow-y: auto;">{{ json_encode($egyptexpressAirwayBill->request_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.response_data') }}
                        </th>
                        <td>
                            <pre style="max-height: 300px; overflow-y: auto;">{{ json_encode($egyptexpressAirwayBill->response_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.egyptexpressAirwayBill.fields.created_at') }}
                        </th>
                        <td>
                            {{ $egyptexpressAirwayBill->created_at ? $egyptexpressAirwayBill->created_at->format(config('panel.date_format') . ' ' . config('panel.time_format')) : '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.egyptexpress-airway-bills.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
