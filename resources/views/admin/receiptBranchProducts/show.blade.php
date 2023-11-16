@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.receiptBranchProduct.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-branch-products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptBranchProduct.fields.id') }}
                        </th>
                        <td>
                            {{ $receiptBranchProduct->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptBranchProduct.fields.name') }}
                        </th>
                        <td>
                            {{ $receiptBranchProduct->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptBranchProduct.fields.price') }}
                        </th>
                        <td>
                            {{ $receiptBranchProduct->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptBranchProduct.fields.price_parts') }}
                        </th>
                        <td>
                            {{ $receiptBranchProduct->price_parts }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptBranchProduct.fields.price_permissions') }}
                        </th>
                        <td>
                            {{ $receiptBranchProduct->price_permissions }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptBranchProduct.fields.receipts') }}
                        </th>
                        <td>
                            @foreach($receiptBranchProduct->receipts as $key => $receipts)
                                <span class="label label-info">{{ $receipts->order_num }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-branch-products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection