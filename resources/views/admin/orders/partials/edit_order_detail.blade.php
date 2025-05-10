@php
    $image = $product->photos[0] ? $product->photos[0]->getUrl('preview2') : '';
    $prices = product_price_in_cart($orderDetail->quantity, $orderDetail->variation, $product);
@endphp
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">تعديل منتج في أوردر {{ $orderDetail->order->order_num ?? '' }}</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form id="add-to-cart-form" action="{{ route('admin.orders.update_order_detail') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $orderDetail->id }}"> 
            <div class="row">
                <div class="col-md-3">
                    <div class="pro-group">
                        <div class="product-img">
                            <div class="media">
                                <div class="img-wraper">
                                    <a href="{{ route('frontend.product', $product->slug) }}">
                                        <img src="{{ $image }}" alt="" class="img-fluid">
                                    </a>
                                    <div class="media-body">
                                        <a href="{{ route('frontend.product', $product->slug) }}">
                                            <h3>{{ $product->name }} <b>
                                                    @if ($orderDetail->variation)
                                                        ( {{ $orderDetail->variation }} )
                                                    @endif
                                                </b></h3>
                                        </a>
                                        <h6> <?php echo $prices['h2']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="pro-group">
                        @if ($product->choice_options != null && count(json_decode($product->choice_options)) > 0)
                            @foreach (json_decode($product->choice_options) as $key => $choice)
                                <h6 class="product-title size-text"> {{ $choice->attribute }} <span>
                                    </span></h6>
                                <div class="size-box" id="{{ $key }}-{{ $choice->attribute }}">
                                    <ul>
                                        @foreach ($choice->values as $key2 => $value)
                                            <li data-attribute="{{ $key }}-{{ $choice->attribute }}"
                                                style="width: fit-content">
                                                <input style="display: none;" type="radio"
                                                    id="{{ $choice->attribute }}-{{ $value }}"
                                                    name="attribute_{{ $choice->attribute }}"
                                                    value="{{ $value }}">
                                                <label
                                                    style="width:100%;height:100%;user-select: none;padding: 6px 12px;"
                                                    for="{{ $choice->attribute }}-{{ $value }}">{{ $value }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="pro-group">
                        @if ($product->colors != null && !empty(json_decode($product->colors)))

                            <h6 class="product-title">{{ __('frontend.product.color') }}</h6>
                            <div class="color-selector inline">
                                <ul>
                                    @if (count(json_decode($product->colors)) > 0)
                                        @foreach (json_decode($product->colors) as $key => $color)
                                            <li>
                                                <input style="display:none" type="radio"
                                                    id="{{ $product->id }}-color-{{ $key }}" name="color">
                                                <label style="background: {{ $color }};"
                                                    for="{{ $product->id }}-color-{{ $key }}"
                                                    data-toggle="tooltip">

                                                </label>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endif

                        @if ($product->special)
                            <input type="hidden" name="variant" id="variant" value="{{ $orderDetail->variation }}">
                            <h5 class="mb-3">{{ __('frontend.product.printed_photos') }}</h5>
                            @if (is_array(json_decode($orderDetail->photos)) && count(json_decode($orderDetail->photos)) > 0)
                                @foreach (json_decode($orderDetail->photos) as $key => $photo)
                                    <div class="row">
                                        <div class="col-md-2">
                                            <button type="button" onclick="delete_this_row(this)"
                                                class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        </div>
                                        <input type="hidden" name="previous_photos[]"
                                            value="{{ $photo->photo ?? '' }}">
                                        <div class="col-md-2">
                                            @isset($photo->photo)
                                                <img src="{{ asset($photo->photo) }}" width="50" height="50"
                                                    alt="">
                                            @endisset
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" value="{{ $photo->note ?? '' }}" name="photos_note[]"
                                                class="form-control" placeholder="ملحوظة علي الصورة">
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <div id="product-images2">
                                @if (is_array(json_decode($orderDetail->photos)) && count(json_decode($orderDetail->photos)) == 0)
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <input type="file" id="photos-1" name="photos[]" class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <input type="text" name="photos_note[]" class="form-control"
                                                id="name"
                                                placeholder="{{ __('frontend.product.photo_note') }}">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-warning mb-3"
                                onclick="add_more_slider_image()">{{ __('frontend.product.add_more') }}</button>

                            <div class="col-12 mb-3">
                                <label>{{ __('frontend.product.description') }}</label>
                                <textarea class="form-control" name="description" placeholder="{{ __('frontend.product.description') }}"
                                    rows="3" required>{{ $orderDetail->description }}</textarea>
                            </div>
                        @endif
                    </div> 
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="required" for="quantity">{{ __('frontend.product.quantity') }}</label>
                            <input class="form-control" type="number" name="quantity" id="quantity" value="{{ $orderDetail->quantity }}" required min="1" max="{{ $product->current_stock }}"> 
                        </div>
                        <div class="form-group col-md-4">
                            <label class="required" for="price">{{ __('frontend.product.cost') }}</label>
                            <input class="form-control" type="number" name="price" id="price" value="{{ $orderDetail->price }}" required> 
                        </div>
                        <div class="form-group col-md-4">
                            <label for="extra_commission">{{ __('cruds.order.extra.extra_commission') }}</label>
                            <input class="form-control" type="number" name="extra_commission" id="extra_commission" value="{{ $orderDetail->extra_commission }}"> 
                        </div>
                        <div class="form-group col-md-4">
                            <label for="variation">{{ __('cruds.order.extra.variation') }}</label>
                            <input class="form-control" type="text" name="variation" id="variation" value="{{ $orderDetail->variation }}"> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="pro-group mb-0">
                <div class="modal-btn">
                    <button type="submit" class="btn btn-success">
                        {{ __('frontend.product.edit_cart') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var photo_id = 2;

    function add_more_slider_image() {
        console.log('test');
        var photoAdd = '<div class="row">';
        photoAdd += '<div class="col-md-1">';
        photoAdd +=
            '<button type="button" onclick="delete_this_row(this)" class="btn btn-danger"><i class="fa fa-trash"></i></button>';
        photoAdd += '</div>';
        photoAdd += '<div class="col-md-7 mb-3">';
        photoAdd += '<input type="file" id="photos-' + photo_id +
            '" name="photos[]" class="form-control" multiple accept="image/*" />';
        photoAdd += '</div>';
        photoAdd += '<div class="col-md-4 mb-3">';
        photoAdd += '<input type="text" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة" >';
        photoAdd += '</div>';
        photoAdd += '</div>';
        $('#product-images2').append(photoAdd);

        photo_id++;
    }
    function delete_this_row(em){
        $(em).closest('.row').remove();
    }
</script>
