@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.receiptPriceViewProduct.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-price-view-products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.id') }}
                        </th>
                        <td>
                            {{ $receiptPriceViewProduct->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.description') }}
                        </th>
                        <td>
                            {{ $receiptPriceViewProduct->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.price') }}
                        </th>
                        <td>
                            {{ $receiptPriceViewProduct->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.quantity') }}
                        </th>
                        <td>
                            {{ $receiptPriceViewProduct->quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.total_cost') }}
                        </th>
                        <td>
                            {{ $receiptPriceViewProduct->total_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptPriceViewProduct.fields.receipt_price_view') }}
                        </th>
                        <td>
                            {{ $receiptPriceViewProduct->receipt_price_view->order_num ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-price-view-products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection