@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.seller.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sellers.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.id') }}
                        </th>
                        <td>
                            {{ $seller->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.user') }}
                        </th>
                        <td>
                            {{ $seller->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.seller_type') }}
                        </th>
                        <td>
                            {{ App\Models\Seller::SELLER_TYPE_SELECT[$seller->seller_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.discount') }}
                        </th>
                        <td>
                            {{ $seller->discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.discount_code') }}
                        </th>
                        <td>
                            {{ $seller->discount_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.order_out_website') }}
                        </th>
                        <td>
                            {{ $seller->order_out_website }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.order_in_website') }}
                        </th>
                        <td>
                            {{ $seller->order_in_website }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.qualification') }}
                        </th>
                        <td>
                            {{ $seller->qualification }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.social_name') }}
                        </th>
                        <td>
                            {{ $seller->social_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.social_link') }}
                        </th>
                        <td>
                            {{ $seller->social_link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.seller_code') }}
                        </th>
                        <td>
                            {{ $seller->seller_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.identity_back') }}
                        </th>
                        <td>
                            @if($seller->identity_back)
                                <a href="{{ $seller->identity_back->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $seller->identity_back->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.seller.fields.identity_front') }}
                        </th>
                        <td>
                            @if($seller->identity_front)
                                <a href="{{ $seller->identity_front->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $seller->identity_front->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sellers.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection