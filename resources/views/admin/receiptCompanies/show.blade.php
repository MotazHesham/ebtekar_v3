@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.receiptCompany.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-companies.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.id') }}
                        </th>
                        <td>
                            {{ $receiptCompany->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.order_num') }}
                        </th>
                        <td>
                            {{ $receiptCompany->order_num }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.client_name') }}
                        </th>
                        <td>
                            {{ $receiptCompany->client_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.client_type') }}
                        </th>
                        <td>
                            {{ App\Models\ReceiptCompany::CLIENT_TYPE_SELECT[$receiptCompany->client_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.phone_number') }}
                        </th>
                        <td>
                            {{ $receiptCompany->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.phone_number_2') }}
                        </th>
                        <td>
                            {{ $receiptCompany->phone_number_2 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.deposit') }}
                        </th>
                        <td>
                            {{ $receiptCompany->deposit }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.total_cost') }}
                        </th>
                        <td>
                            {{ $receiptCompany->total_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.calling') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptCompany->calling ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.quickly') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptCompany->quickly ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.done') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptCompany->done ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.no_answer') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptCompany->no_answer ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.supplied') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptCompany->supplied ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.printing_times') }}
                        </th>
                        <td>
                            {{ $receiptCompany->printing_times }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.deliver_date') }}
                        </th>
                        <td>
                            {{ $receiptCompany->deliver_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.date_of_receiving_order') }}
                        </th>
                        <td>
                            {{ $receiptCompany->date_of_receiving_order }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.send_to_playlist_date') }}
                        </th>
                        <td>
                            {{ $receiptCompany->send_to_playlist_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.send_to_delivery_date') }}
                        </th>
                        <td>
                            {{ $receiptCompany->send_to_delivery_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.done_time') }}
                        </th>
                        <td>
                            {{ $receiptCompany->done_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.shipping_country_name') }}
                        </th>
                        <td>
                            {{ $receiptCompany->shipping_country_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.shipping_country_cost') }}
                        </th>
                        <td>
                            {{ $receiptCompany->shipping_country_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.shipping_address') }}
                        </th>
                        <td>
                            {{ $receiptCompany->shipping_address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.description') }}
                        </th>
                        <td>
                            {!! $receiptCompany->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.note') }}
                        </th>
                        <td>
                            {{ $receiptCompany->note }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.cancel_reason') }}
                        </th>
                        <td>
                            {{ $receiptCompany->cancel_reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.delay_reason') }}
                        </th>
                        <td>
                            {{ $receiptCompany->delay_reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.photos') }}
                        </th>
                        <td>
                            @foreach($receiptCompany->photos as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.delivery_status') }}
                        </th>
                        <td>
                            {{ App\Models\ReceiptCompany::DELIVERY_STATUS_SELECT[$receiptCompany->delivery_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.payment_status') }}
                        </th>
                        <td>
                            {{ App\Models\ReceiptCompany::PAYMENT_STATUS_SELECT[$receiptCompany->payment_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.playlist_status') }}
                        </th>
                        <td>
                            {{ App\Models\ReceiptCompany::PLAYLIST_STATUS_SELECT[$receiptCompany->playlist_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.staff') }}
                        </th>
                        <td>
                            {{ $receiptCompany->staff->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.designer') }}
                        </th>
                        <td>
                            {{ $receiptCompany->designer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.preparer') }}
                        </th>
                        <td>
                            {{ $receiptCompany->preparer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.manufacturer') }}
                        </th>
                        <td>
                            {{ $receiptCompany->manufacturer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.shipmenter') }}
                        </th>
                        <td>
                            {{ $receiptCompany->shipmenter->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.delivery_man') }}
                        </th>
                        <td>
                            {{ $receiptCompany->delivery_man->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptCompany.fields.shipping_country') }}
                        </th>
                        <td>
                            {{ $receiptCompany->shipping_country->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-companies.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection