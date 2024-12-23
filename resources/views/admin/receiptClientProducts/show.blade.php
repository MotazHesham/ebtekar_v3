@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.receiptClientProduct.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-client-products.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.receiptClientProduct.fields.id') }}
                        </th>
                        <td>
                            {{ $receiptClientProduct->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.receiptClientProduct.fields.name') }}
                        </th>
                        <td>
                            {{ $receiptClientProduct->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.receiptClientProduct.fields.price') }}
                        </th>
                        <td>
                            {{ $receiptClientProduct->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.receiptClientProduct.fields.receipts') }}
                        </th>
                        <td>
                            @foreach($receiptClientProduct->receipts as $key => $receipts)
                                <span class="label label-info">{{ $receipts->order_num }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-client-products.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection