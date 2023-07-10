
@php
    $image = $product->photos[0] ? $product->photos[0]->getUrl('preview2') : '';
@endphp 
<div class="row">
    <div class="col-lg-6 col-xs-12">
        <div class="quick-view-img">
            <img src="{{ $image }}" alt=""
                class="img-fluid bg-img">
        </div>
    </div>
    <div class="col-lg-6 rtl-text">
        <div class="product-right">
            <div class="pro-group">
                <h2>
                    {{ $product->name }}
                </h2>
                <ul class="pro-price">
                    @if($product->discount > 0)
                        <li>{{ front_calc_product_currency($product->unit_price,$product->weight)['as_text']}}</li>
                        <li><span>{{ front_calc_product_currency($product->calc_discount($product->unit_price),$product->weight)['as_text']}}</span></li>
                    @else
                        <li>{{ front_calc_product_currency($product->unit_price,$product->weight)['as_text']}}</li>
                    @endif
                </ul>
                <div class="revieu-box">
                    <ul>
                        @include('frontend.partials.rate',['rate' => $product->rating])
                    </ul>
                    @php
                        $count_reviews = $product->reviews()->count()
                    @endphp
                    <a href="#"><span> @if($count_reviews > 0) ({{$count_reviews}} {{ trans('frontend.product.reviews') }}) @endif</span></a>
                </div>

            </div>
            <div class="pro-group">
                <h6 class="product-title">  {{ trans('frontend.product.product_description') }}</h6>
                <p>
                    <?php echo $product->description; ?>
                </p>
            </div>
            <div class="pro-group pb-0"> 
                @if ($product->choice_options != null && count(json_decode($product->choice_options)) > 0)
                    @foreach (json_decode($product->choice_options) as $key => $choice) 
                        <h6 class="product-title size-text"> {{ $choice->attribute }} <span>
                            </span></h6>
                        <div class="size-box" id="{{$key}}-{{$choice->attribute}}">
                            <ul>
                                @foreach ($choice->values as $key2 => $value)
                                    <li data-attribute="{{$key}}-{{$choice->attribute}}"  style="width: fit-content">
                                        <input style="display: none;" type="radio" id="{{ $choice->attribute }}-{{ $value }}" name="attribute_{{ $choice->attribute }}" value="{{ $value }}" >
                                        <label style="width:100%;height:100%;user-select: none;padding: 6px 12px;" for="{{ $choice->attribute }}-{{ $value }}">{{ $value }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                @endif

                @if ($product->colors != null && !empty(json_decode($product->colors)))

                    <h6 class="product-title">{{ trans('frontend.product.color') }}</h6>
                    <div class="color-selector inline">
                        <ul>
                            @if (count(json_decode($product->colors)) > 0)
                                @foreach (json_decode($product->colors) as $key => $color)
                                    <li>
                                        <input style="display:none" type="radio" id="{{ $product->id }}-color-{{ $key }}" name="color" >
                                        <label style="background: {{ $color }};" for="{{ $product->id }}-color-{{ $key }}" data-toggle="tooltip" >

                                        </label>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                @endif

                <div class="product-buttons">
                    <a href="{{ route('frontend.product',$product->slug) }}" class="btn btn-normal tooltip-top"
                        data-tippy-content="view detail">
                        {{ trans('frontend.product.show_details') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
