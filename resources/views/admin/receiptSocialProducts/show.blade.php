@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.receiptSocialProduct.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.receipt-social-products.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.receiptSocialProduct.fields.id') }}
                        </th>
                        <td>
                            {{ $receiptSocialProduct->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.receiptSocialProduct.fields.name') }}
                        </th>
                        <td>
                            {{ $receiptSocialProduct->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.receiptSocialProduct.fields.price') }}
                        </th>
                        <td>
                            {{ $receiptSocialProduct->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.receiptSocialProduct.fields.commission') }}
                        </th>
                        <td>
                            {{ $receiptSocialProduct->commission }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.receiptSocialProduct.fields.photos') }}
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
                            Shopify Images
                        </th>
                        <td>
                            @if($receiptSocialProduct->shopify_images && count(json_decode($receiptSocialProduct->shopify_images)) > 0)
                                @foreach(json_decode($receiptSocialProduct->shopify_images) as $key => $shopify_image)
                                    <a href="{{ $shopify_image }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $shopify_image }}" style="width: 100px; height: 100px;">
                                    </a>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.receiptSocialProduct.fields.receipts') }}
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
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection