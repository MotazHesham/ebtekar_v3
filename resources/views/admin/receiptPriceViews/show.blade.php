@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.receiptPriceView.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-price-views.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.id') }}
                        </th>
                        <td>
                            {{ $receiptPriceView->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.order_num') }}
                        </th>
                        <td>
                            {{ $receiptPriceView->order_num }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.date_of_receiving_order') }}
                        </th>
                        <td>
                            {{ $receiptPriceView->date_of_receiving_order }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.client_name') }}
                        </th>
                        <td>
                            {{ $receiptPriceView->client_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.phone_number') }}
                        </th>
                        <td>
                            {{ $receiptPriceView->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.total_cost') }}
                        </th>
                        <td>
                            {{ $receiptPriceView->total_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.place') }}
                        </th>
                        <td>
                            {{ $receiptPriceView->place }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.relate_duration') }}
                        </th>
                        <td>
                            {{ $receiptPriceView->relate_duration }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.supply_duration') }}
                        </th>
                        <td>
                            {{ $receiptPriceView->supply_duration }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.payment') }}
                        </th>
                        <td>
                            {{ $receiptPriceView->payment }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.added_value') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $receiptPriceView->added_value ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceView.fields.printing_times') }}
                        </th>
                        <td>
                            {{ $receiptPriceView->printing_times }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-price-views.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection