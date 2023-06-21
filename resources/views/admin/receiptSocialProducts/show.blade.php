@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.receiptSocialProduct.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-social-products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocialProduct.fields.id') }}
                        </th>
                        <td>
                            {{ $receiptSocialProduct->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocialProduct.fields.name') }}
                        </th>
                        <td>
                            {{ $receiptSocialProduct->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocialProduct.fields.price') }}
                        </th>
                        <td>
                            {{ $receiptSocialProduct->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocialProduct.fields.commission') }}
                        </th>
                        <td>
                            {{ $receiptSocialProduct->commission }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocialProduct.fields.photos') }}
                        </th>
                        <td>
                            @foreach($receiptSocialProduct->photos as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.receiptSocialProduct.fields.receipts') }}
                        </th>
                        <td>
                            @foreach($receiptSocialProduct->receipts as $key => $receipts)
                                <span class="badge badge-info">{{ $receipts->order_num }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-social-products.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection