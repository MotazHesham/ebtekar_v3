@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.receiptSocial.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-socials.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.id') }}
                        </th>
                        <td>
                            {{ $receiptSocial->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.order_num') }}
                        </th>
                        <td>
                            {{ $receiptSocial->order_num }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.client_name') }}
                        </th>
                        <td>
                            {{ $receiptSocial->client_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.client_type') }}
                        </th>
                        <td>
                            {{ App\Models\ReceiptSocial::CLIENT_TYPE_SELECT[$receiptSocial->client_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.phone_number') }}
                        </th>
                        <td>
                            {{ $receiptSocial->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.phone_number_2') }}
                        </th>
                        <td>
                            {{ $receiptSocial->phone_number_2 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.deposit') }}
                        </th>
                        <td>
                            {{ $receiptSocial->deposit }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.discount') }}
                        </th>
                        <td>
                            {{ $receiptSocial->discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.commission') }}
                        </th>
                        <td>
                            {{ $receiptSocial->commission }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.extra_commission') }}
                        </th>
                        <td>
                            {{ $receiptSocial->extra_commission }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.total_cost') }}
                        </th>
                        <td>
                            {{ $receiptSocial->total_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.done') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptSocial->done ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.quickly') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptSocial->quickly ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.confirm') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptSocial->confirm ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.returned') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptSocial->returned ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.supplied') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptSocial->supplied ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.printing_times') }}
                        </th>
                        <td>
                            {{ $receiptSocial->printing_times }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.shipping_country_name') }}
                        </th>
                        <td>
                            {{ $receiptSocial->shipping_country_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.shipping_country_cost') }}
                        </th>
                        <td>
                            {{ $receiptSocial->shipping_country_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.shipping_address') }}
                        </th>
                        <td>
                            {{ $receiptSocial->shipping_address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.date_of_receiving_order') }}
                        </th>
                        <td>
                            {{ $receiptSocial->date_of_receiving_order }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.deliver_date') }}
                        </th>
                        <td>
                            {{ $receiptSocial->deliver_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.send_to_delivery_date') }}
                        </th>
                        <td>
                            {{ $receiptSocial->send_to_delivery_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.send_to_playlist_date') }}
                        </th>
                        <td>
                            {{ $receiptSocial->send_to_playlist_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.done_time') }}
                        </th>
                        <td>
                            {{ $receiptSocial->done_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.cancel_reason') }}
                        </th>
                        <td>
                            {{ $receiptSocial->cancel_reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.delay_reason') }}
                        </th>
                        <td>
                            {{ $receiptSocial->delay_reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.delivery_status') }}
                        </th>
                        <td>
                            {{ App\Models\ReceiptSocial::DELIVERY_STATUS_SELECT[$receiptSocial->delivery_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.note') }}
                        </th>
                        <td>
                            {{ $receiptSocial->note }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.payment_status') }}
                        </th>
                        <td>
                            {{ App\Models\ReceiptSocial::PAYMENT_STATUS_SELECT[$receiptSocial->payment_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.playlist_status') }}
                        </th>
                        <td>
                            {{ App\Models\ReceiptSocial::PLAYLIST_STATUS_SELECT[$receiptSocial->playlist_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.staff') }}
                        </th>
                        <td>
                            {{ $receiptSocial->staff->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.designer') }}
                        </th>
                        <td>
                            {{ $receiptSocial->designer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.preparer') }}
                        </th>
                        <td>
                            {{ $receiptSocial->preparer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.manufacturer') }}
                        </th>
                        <td>
                            {{ $receiptSocial->manufacturer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.shipmenter') }}
                        </th>
                        <td>
                            {{ $receiptSocial->shipmenter->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.delivery_man') }}
                        </th>
                        <td>
                            {{ $receiptSocial->delivery_man->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.shipping_country') }}
                        </th>
                        <td>
                            {{ $receiptSocial->shipping_country->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocial.fields.socials') }}
                        </th>
                        <td>
                            @foreach($receiptSocial->socials as $key => $socials)
                                <span class="label label-info">{{ $socials->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-socials.index') }}">
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
            <a class="nav-link" href="#receipts_receipt_social_products" role="tab" data-toggle="tab">
                {{ trans('cruds.receiptSocialProduct.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="receipts_receipt_social_products">
            @includeIf('admin.receiptSocials.relationships.receiptsReceiptSocialProducts', ['receiptSocialProducts' => $receiptSocial->receiptsReceiptSocialProducts])
        </div>
    </div>
</div>

@endsection