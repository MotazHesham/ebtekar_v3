@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.receiptOutgoingProduct.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-outgoing-products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.id') }}
                        </th>
                        <td>
                            {{ $receiptOutgoingProduct->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.description') }}
                        </th>
                        <td>
                            {{ $receiptOutgoingProduct->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.price') }}
                        </th>
                        <td>
                            {{ $receiptOutgoingProduct->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.quantity') }}
                        </th>
                        <td>
                            {{ $receiptOutgoingProduct->quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.total_cost') }}
                        </th>
                        <td>
                            {{ $receiptOutgoingProduct->total_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptOutgoingProduct.fields.receipt_outgoing') }}
                        </th>
                        <td>
                            {{ $receiptOutgoingProduct->receipt_outgoing->order_num ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-outgoing-products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection