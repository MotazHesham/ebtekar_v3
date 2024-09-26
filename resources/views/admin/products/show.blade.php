@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.product.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.products.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.id') }}
                        </th>
                        <td>
                            {{ $product->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.name') }}
                        </th>
                        <td>
                            {{ $product->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.added_by') }}
                        </th>
                        <td>
                            {{ $product->added_by }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.unit_price') }}
                        </th>
                        <td>
                            {{ $product->unit_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.purchase_price') }}
                        </th>
                        <td>
                            {{ $product->purchase_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.slug') }}
                        </th>
                        <td>
                            {{ $product->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.attributes') }}
                        </th>
                        <td>
                            {{ $product->attributes }}
                        </td>
                    </tr> 
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.colors') }}
                        </th>
                        <td>
                            @if (count(json_decode($product->colors)) > 0)
                                @foreach (json_decode($product->colors) as $key => $color)
                                    <span style="background: {{$color}}">{{$color}}</span>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.tags') }}
                        </th>
                        <td>
                            {{ $product->tags }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.video_provider') }}
                        </th>
                        <td>
                            {{ $product->video_provider }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.video_link') }}
                        </th>
                        <td>
                            {{ $product->video_link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.description') }}
                        </th>
                        <td>
                            {!! $product->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.photos') }}
                        </th>
                        <td>
                            <form action="{{ route('admin.products.sorting_images') }}" method="POST">
                                @csrf
                                <div class="row">
                                    @foreach($product->photos as $key => $media)
                                        <div class="col-md-2">
                                            <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $media->getUrl('thumb') }}">
                                            </a>
                                            <input type="number" class="form-control" name="media[{{ $media->id }}]" value="{{ $media->order_column }}">
                                        </div>
                                    @endforeach
                                </div>
                                <input type="submit" class="btn btn-info" type="submit" value="submit">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.pdf') }}
                        </th>
                        <td>
                            @if($product->pdf)
                                <a href="{{ $product->pdf->getUrl() }}" target="_blank">
                                    {{ __('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.discount_type') }}
                        </th>
                        <td>
                            {{ App\Models\Product::DISCOUNT_TYPE_SELECT[$product->discount_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.discount') }}
                        </th>
                        <td>
                            {{ $product->discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.meta_title') }}
                        </th>
                        <td>
                            {{ $product->meta_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.meta_description') }}
                        </th>
                        <td>
                            {{ $product->meta_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.flash_deal') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $product->flash_deal ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.published') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $product->published ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.featured') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $product->featured ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.todays_deal') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $product->todays_deal ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.rating') }}
                        </th>
                        <td>
                            {{ $product->rating }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.current_stock') }}
                        </th>
                        <td>
                            {{ $product->current_stock }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.user') }}
                        </th>
                        <td>
                            {{ $product->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.category') }}
                        </th>
                        <td>
                            {{ $product->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.sub_category') }}
                        </th>
                        <td>
                            {{ $product->sub_category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.product.fields.sub_sub_category') }}
                        </th>
                        <td>
                            {{ $product->sub_sub_category->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.products.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection