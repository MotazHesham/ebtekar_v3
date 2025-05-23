@extends('frontend.layout.app')

@section('meta_title'){{ $product->meta_title }}@stop

@section('meta_description'){{ $product->meta_description }}@stop

@section('meta_keywords'){{ $product->tags }}@stop

@section('meta')

    @php
        $meta_image = isset($product->photos[0]) ? $product->photos[0]->getUrl('preview2') : '';
        $site_settings = get_site_setting();
    @endphp
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $product->meta_title }}">
    <meta itemprop="description" content="{{ $product->meta_description }}">
    <meta itemprop="image" content="{{ $meta_image }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $product->meta_title }}">
    <meta name="twitter:description" content="{{ $product->meta_description }}">
    <meta name="twitter:creator"
        content="@author_handle">
    <meta name="twitter:image" content="{{ $meta_image }}">
    <meta name="twitter:data1" content="{{ front_calc_product_currency($product->calc_discount($product->unit_price),$product->weight)['as_text']}}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $product->meta_title }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('frontend.product', $product->slug) }}" />
    <meta property="og:image" content="{{ $meta_image }}" />
    <meta property="og:description" content="{{ $product->meta_description }}" />
    <meta property="og:site_name" content="{{ $site_settings->site_name }}" />
    <meta property="og:price:amount" content="{{ front_calc_product_currency($product->calc_discount($product->unit_price),$product->weight)['as_text']}}" /> 
@endsection  

@section('styles')
    <style>
        
.product-slick img,
.rtl-product-slick img,
.product-right-slick img,
.rtl-product-right-slick img {
  width: 100%; }

.product-slick .slick-prev,
.product-slick .slick-next,
.rtl-product-slick .slick-prev,
.rtl-product-slick .slick-next,
.product-right-slick .slick-prev,
.product-right-slick .slick-next,
.rtl-product-right-slick .slick-prev,
.rtl-product-right-slick .slick-next {
  opacity: 0;
  -webkit-transform: scale(1);
          transform: scale(1);
  -webkit-transition: all 0.5s ease;
  transition: all 0.5s ease;
  margin-top: -10px; }
  .product-slick .slick-prev:before,
  .product-slick .slick-next:before,
  .rtl-product-slick .slick-prev:before,
  .rtl-product-slick .slick-next:before,
  .product-right-slick .slick-prev:before,
  .product-right-slick .slick-next:before,
  .rtl-product-right-slick .slick-prev:before,
  .rtl-product-right-slick .slick-next:before {
    font: normal normal normal 14px/1 FontAwesome;
    opacity: 1;
    color: black;
    background-color: #ffffff;
    border-radius: 100%;
    height: 100%;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center; }

.product-slick .slick-next,
.rtl-product-slick .slick-next,
.product-right-slick .slick-next,
.rtl-product-right-slick .slick-next {
  right: 1px; }
  .product-slick .slick-next:before,
  .rtl-product-slick .slick-next:before,
  .product-right-slick .slick-next:before,
  .rtl-product-right-slick .slick-next:before {
    content: "\f105"; }

.product-slick .slick-prev,
.rtl-product-slick .slick-prev,
.product-right-slick .slick-prev,
.rtl-product-right-slick .slick-prev {
  left: 1px;
  z-index: 1; }
  .product-slick .slick-prev:before,
  .rtl-product-slick .slick-prev:before,
  .product-right-slick .slick-prev:before,
  .rtl-product-right-slick .slick-prev:before {
    content: "\f104"; }

.product-slick .slick-slide > div,
.rtl-product-slick .slick-slide > div,
.product-right-slick .slick-slide > div,
.rtl-product-right-slick .slick-slide > div {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  margin-bottom: 15px; }

.product-slick:hover .slick-prev,
.product-slick:hover .slick-next,
.rtl-product-slick:hover .slick-prev,
.rtl-product-slick:hover .slick-next,
.product-right-slick:hover .slick-prev,
.product-right-slick:hover .slick-next,
.rtl-product-right-slick:hover .slick-prev,
.rtl-product-right-slick:hover .slick-next {
  opacity: 1;
  -webkit-transform: scale(1.05);
          transform: scale(1.05);
  -webkit-transition: all 0.5s ease;
  transition: all 0.5s ease; }
  .product-slick:hover .slick-prev:before,
  .product-slick:hover .slick-next:before,
  .rtl-product-slick:hover .slick-prev:before,
  .rtl-product-slick:hover .slick-next:before,
  .product-right-slick:hover .slick-prev:before,
  .product-right-slick:hover .slick-next:before,
  .rtl-product-right-slick:hover .slick-prev:before,
  .rtl-product-right-slick:hover .slick-next:before {
    opacity: 1; }

.product-slick:hover .slick-next,
.rtl-product-slick:hover .slick-next,
.product-right-slick:hover .slick-next,
.rtl-product-right-slick:hover .slick-next {
  right: 20px; }

.product-slick:hover .slick-prev,
.rtl-product-slick:hover .slick-prev,
.product-right-slick:hover .slick-prev,
.rtl-product-right-slick:hover .slick-prev {
  left: 20px; }
    </style>
@endsection
@section('content') 

    {{-- this is for share product in facebook --}}
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    {{-- this is for share product in twitter --}}
    <script>window.twttr = (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0],
        t = window.twttr || {};
        if (d.getElementById(id)) return t;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://platform.twitter.com/widgets.js";
        fjs.parentNode.insertBefore(js, fjs);
    
        t._e = [];
        t.ready = function(f) {
        t._e.push(f);
        };
    
        return t;
    }(document, "script", "twitter-wjs"));</script>

    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>{{ __('frontend.product.products') }}</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">{{ __('frontend.about.home') }}</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)">{{ __('frontend.product.products') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- section start -->
    <section class="section-big-pt-space b-g-light">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 position-relative">
                        <div class="product-slick no-arrow"> 
                            @foreach ($product->photos as $key => $media)
                                <div><img src="{{ $media->getUrl() }}" alt="{{ $product->name }}"
                                        class="img-fluid image_zoom_cls-{{ $key }} product-image" data-full-image="{{ $media->getUrl() }}"  onerror="this.onerror=null;this.src='{{ asset('placeholder.jpg') }}';"></div>
                            @endforeach 
                        </div>
                        <div class="row">
                            <div class="col-12 p-0">
                                <div class="slider-nav"> 
                                    @foreach ($product->photos as $key => $media)
                                        <div><img src="{{ $media->getUrl('preview') }}" alt="{{ $product->name }}"
                                                class="img-fluid  image_zoom_cls-{{ $key }}"  onerror="this.onerror=null;this.src='{{ asset('placeholder.jpg') }}';"></div>
                                    @endforeach 
                                </div>
                            </div>
                        </div>
                        {{-- data-bs-toggle="modal" data-bs-target="2view360" --}}
                        <div class="image-360" data-bs-toggle="modal" data-bs-target="#view360"> 
                            {{-- <a href=" "> --}}
                                <img src="{{ asset('frontend/assets/images/360deg.png') }}" class="img-fluid" alt="360deg">
                            {{-- </a> --}}
                        </div>
                    </div>
                    <div class="col-lg-7 rtl-text">
                        <div class="product-right">
                            <div class="pro-group">
                                <h2>{{ $product->name }}</h2>
                                <ul class="pro-price" id="">
                                    @if ($product->discount > 0)
                                        <li id="product-price-for-variant" style="font-size: 30px;">{{ front_calc_product_currency($product->unit_price, $product->weight)['as_text'] }}</li>
                                        <li><span id="product-price-calc-discount" style="font-size: 30px;"> {{ front_calc_product_currency($product->calc_discount($product->unit_price), $product->weight)['as_text'] }}</span></li>
                                    @else
                                        <li id="product-price-for-variant" style="font-size: 30px;">{{ front_calc_product_currency($product->unit_price, $product->weight)['as_text'] }}</li>
                                    @endif
                                    @if (auth()->check() && auth()->user()->user_type == 'seller')
                                        <li id="product-commission-for-variant">
                                            <div class="text-center"> <small> {{ __('frontend.product.commission') }}:<b> {{ front_calc_commission_currency($product->unit_price, $product->purchase_price)['as_text'] }} </b> </small> </div>
                                        </li> 
                                    @endif
                                </ul>
                                <div class="revieu-box">
                                    <ul>
                                        @include('frontend.partials.rate', ['rate' => $product->rating])
                                    </ul>
                                    @php
                                        $count_reviews = $product->reviews()->count()
                                    @endphp
                                    <a href="#"><span> @if ($count_reviews > 0) ({{ $count_reviews }} {{ __('frontend.product.reviews') }}) @endif</span></a>
                                </div> 
                            </div>
                            <form id="add-to-cart-form" action="{{ route('frontend.cart.add') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="variant" id="variant">
                                @if ($product->special)
                                    {{-- Custom the product --}}
                                    <div class="modal fade" id="requist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">{{ __('frontend.product.custom_product') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body"> 
                                                    @if($product->require_photos)
                                                        <h5 class="mb-3">{{ __('frontend.product.printed_photos') }}</h5>
                                                        <div id="product-images">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <input type="file" id="photos-1" name="photos[]" class="form-control"> 
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <input type="text" name="photos_note[]" class="form-control" id="name" placeholder="{{ __('frontend.product.photo_note') }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-warning mb-3" onclick="add_more_slider_image()">{{ __('frontend.product.add_more') }}</button>
                                                    @endif
                                                    <div class="col-12 mb-3">
                                                        <label>{{ __('frontend.product.description') }}</label>
                                                        <textarea class="form-control" name="description" placeholder="اكتب هنا الاسم والتفاصيل المراد طباعتها على المنتج" rows="3" required></textarea>
                                                    </div>
                                    
                                                    <button type="submit" class="btn btn-rounded black-btn me-3">{{ __('frontend.product.add_to_cart') }}</button> 
                                                    <button type="submit" name="buy_now" class="btn btn-rounded black-btn me-3">اشتري الان</button> 
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                @endif

                                <div id="selectSize" class="pro-group addeffect-section product-description border-product mb-0"> 
                                    @if ($product->attribute_options != null && count(json_decode($product->attribute_options)) > 0)
                                        @foreach (json_decode($product->attribute_options) as $key => $attr_optn)
                                            @php
                                                $attribute = \App\Models\Attribute::find($attr_optn->attribute_id); 
                                            @endphp
                                            <h6 class="product-title size-text"> {{ $attribute ? $attribute->name : '' }} <span>
                                                </span></h6>
                                            <div class="size-box" id="{{ $key }}-{{ $attr_optn->attribute_id }}">
                                                <ul>
                                                    @foreach ($attr_optn->values as $key2 => $value)
                                                        <li data-attribute="{{ $key }}-{{ $attr_optn->attribute_id }}" @if ($key2 == 0) class="active" @endif  style="width: fit-content;">
                                                            <input style="display: none" type="radio" id="{{ $attr_optn->attribute_id }}-{{ $value }}" name="attribute_{{ $attr_optn->attribute_id }}" value="{{ $value }}" @if ($key2 == 0) checked @endif>
                                                            <label style="width:100%;height:100%;user-select: none;padding: 6px 12px;" for="{{ $attr_optn->attribute_id }}-{{ $value }}">{{ $value }}</label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    @endif

                                    @if ($product->colors != null && !empty(json_decode($product->colors)))

                                        <h6 class="product-title">{{ __('frontend.product.color') }}</h6>
                                        <div class="color-selector inline">
                                            <ul>
                                                @if (count(json_decode($product->colors)) > 0)
                                                    @foreach (json_decode($product->colors) as $key => $color)
                                                        <li>
                                                            <input style="display: none" type="radio" id="{{ $product->id }}-color-{{ $key }}" name="color" value="{{ $color }}" @if ($key == 0) checked @endif>

                                                            <label style="background: {{ $color }};" class="color-{{ $key + 1 }}   @if ($key == 0) active @endif" for="{{ $product->id }}-color-{{ $key }}">

                                                            </label>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    @endif


                                    <h6 class="product-title">{{ __('frontend.product.quantity') }}</h6>
                                    <div class="qty-box">
                                        <div class="input-group">
                                            <button class="qty-minus" type="button"></button>
                                            <input class="qty-adj form-control" type="number" value="1" name="quantity" id="available-quantity-input"/>
                                            <button class="qty-plus" type="button"></button>
                                        </div>
                                        &nbsp;&nbsp;
                                        <b>({{ __('frontend.product.available') }} <span id="available-quantity-span">{{ $product->current_stock }}</span>)</b>
                                    </div> 

                                    <div class="product-buttons">
                                        @if ($product->current_stock > 0)
                                            @if ($product->special)
                                                <a href="" 
                                                class="btn cart-btn btn-normal tooltip-top" data-tippy-content="Add to cart" data-bs-toggle="modal" data-bs-target="#requist">{{ __('frontend.product.custom_product') }}</a>
                                            @else
                                                <button type="submit" id="cartEffect"
                                                    class="btn cart-btn btn-normal tooltip-top" data-tippy-content="Add to cart">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    {{ __('frontend.product.add_to_cart') }}
                                                </button>
                                                <button type="submit" name="buy_now" id="cartEffect"
                                                    class="btn cart-btn btn-normal tooltip-top" data-tippy-content="Buy Now">
                                                    <i class="fa fa-dollar"></i>
                                                    اشتري الأن
                                                </button>
                                            @endif
                                        @else 
                                            <button type="button"
                                                class="btn cart-btn btn-danger tooltip-top" data-tippy-content="Add to cart">
                                                <i class="fa fa-shopping-cart"></i>
                                                Out Of Stock
                                            </button>
                                        @endif
                                        <a href="{{ route('frontend.wishlist.add', $product->slug) }}" class="btn btn-normal add-to-wish tooltip-top"
                                            data-tippy-content="Add to wishlist">
                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                        </a>
                                    </div>

                                </div>
                            </form> 
                            
                            <div class="pro-group">
                                <h6 class="product-title">{{ __('frontend.product.product_description') }}</h6>
                                <p>
                                    <?php echo $product->description; ?>
                                </p>
                            </div>

                            <div class="pro-group pb-0">
                                @php($en_route_product = route('frontend.en_product', $product->id))
                                <h6 class="product-title"> <a title="Copy Product Link" href="#" onclick="copy_to_clipboard('{{ $en_route_product }}')"><i class="fa fa-copy"></i></a> {{ __('frontend.product.share') }}</h6>
                                <div style="display: flex;justify-content: space-evenly;">
                                    <div class="fb-share-button"  data-href="{{ route('frontend.product', $product->slug) }}"  data-layout="button_count">
                                    </div>
                                    <div>
                                        <a class="twitter-share-button" href="https://twitter.com/intent/tweet" > Tweet</a> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section ends -->


    <!-- product-tab starts -->
    <section class=" tab-product  tab-exes ">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-lg-12 ">
                    <div class=" creative-card creative-inner">
                        <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist"> 
                            <li class="nav-item"><a class="nav-link active" id="contact-top-tab" data-bs-toggle="tab"
                                    href="#top-contact" role="tab" aria-selected="false">{{ __('frontend.product.video') }}</a>
                                <div class="material-border"></div>
                            </li>
                            <li class="nav-item"><a class="nav-link" id="review-top-tab" data-bs-toggle="tab"
                                    href="#top-review" role="tab" aria-selected="false">{{ __('frontend.product.reviews') }}</a>
                                <div class="material-border"></div>
                            </li>
                        </ul>
                        <div class="tab-content nav-material" id="top-tabContent"> 
                            <div class="tab-pane fade show active" id="top-contact" role="tabpanel"
                                aria-labelledby="contact-top-tab">
                                <div class="mt-4 text-center">
                                    <!-- 16:9 aspect ratio -->
                                    <div class="embed-responsive embed-responsive-16by9 mb-5">
                                        @if ($product->video_provider == 'youtube' && $product->video_link != null)
                                            {!! $product->video_link !!}
                                        @elseif ($product->video_provider == 'dailymotion' && $product->video_link != null)
                                            <iframe class="embed-responsive-item" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $product->video_link)[1] ?? '' }}"></iframe>
                                        @elseif ($product->video_provider == 'vimeo' && $product->video_link != null)
                                            <iframe src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $product->video_link)[1] ?? '' }}" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                                @auth
                                    @if (!\App\Models\Review::where('user_id', Auth::id())->where('product_id', $product->id)->first())
                                        <form class="theme-form" method="POST" action="{{ route('frontend.product.rate') }}">
                                            @csrf
                                            <input type="hidden" name="rating" id="user_rate" value="4">
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div class="media">
                                                        <label>{{ __('frontend.product.rate') }}</label>
                                                        <div class="media-body ms-3">
                                                            <div class="product">
                                                                <div class="product-box">
                                                                    <div class="product-detail product-detail2 ">
                                                                        <ul id="user-rating-choice" style="font-size: 30px; ">
                                                                            <li><i class="fa fa-star" onclick="change_rating(1)"></i></li>
                                                                            <li><i class="fa fa-star" onclick="change_rating(2)"></i></li>
                                                                            <li><i class="fa fa-star" onclick="change_rating(3)"></i></li>
                                                                            <li><i class="fa fa-star" onclick="change_rating(4)"></i></li>
                                                                            <li><i class="fa fa-star-o" onclick="change_rating(5)"></i></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-md-12">
                                                    <label>{{ __('frontend.product.comment') }}</label>
                                                    <textarea class="form-control" placeholder="{{ __('frontend.product.write_comment') }}" name="comment" rows="6" required></textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <button class="btn btn-normal" type="submit">{{ __('frontend.product.send') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif @endauth
                                <div style="max-height:
        400px; overflow-x: hidden; overflow-y: scroll; padding: 25px;">
    @foreach ($reviews as $review)
        <div class="card mb-4">
            <div class="card-header" style="display: flex">
                <div>
                    {{ $review->user->name ?? '' }}
                </div>
                <div>&nbsp;</div>
                <ul>
                    @include('frontend.partials.rate', ['rate' => $review->rating])
                </ul>
            </div>
            <div class="card-body">
                <?php echo $review->comment; ?>
            </div>
        </div>
    @endforeach
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </section>
    <!-- product-tab ends -->

    <!-- related products -->
    <section class="section-big-py-space  ratio_asos b-g-light product" style="direction: ltr;">
        <div class="container">
            <div class="row">
                <div class="col-12 product-related">
                    <h2> {{ __('frontend.product.related_products') }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col pr-0">
                    <div class="product-slide-5 product-m no-arrow">
                        @foreach ($related_products as $key => $related_product)
                            @include('frontend.partials.single-product', [
                                'product' => $related_product,
                                'preview' => 'preview2',
                            ])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- related products -->


    <!-- 360 view modal popup start-->
    <div class="modal fade bd-example-modal-lg theme-modal" id="view360" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content view360-modal">
                <div class="modal-header">
                    <h5 class="modal-title">360 View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="product-right" style="position: absolute;">
                        <div class="pro-group">
                            <h2>{{ $product->name }}</h2>
                            <ul class="pro-price" id="">
                                @if ($product->discount > 0)
                                    <li id="product-price-for-variant" style="font-size: 30px;">
                                        {{ front_calc_product_currency($product->unit_price, $product->weight)['as_text'] }}
                                    </li>
                                    <li><span id="product-price-calc-discount" style="font-size: 30px;">
                                            {{ front_calc_product_currency($product->calc_discount($product->unit_price), $product->weight)['as_text'] }}</span>
                                    </li>
                                @else
                                    <li id="product-price-for-variant" style="font-size: 30px;">
                                        {{ front_calc_product_currency($product->unit_price, $product->weight)['as_text'] }}
                                    </li>
                                @endif
                                @if (auth()->check() && auth()->user()->user_type == 'seller')
                                    <li id="product-commission-for-variant">
                                        <div class="text-center"> <small> {{ __('frontend.product.commission') }}:<b>
                                                    {{ front_calc_commission_currency($product->unit_price, $product->purchase_price)['as_text'] }}
                                                </b> </small> </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <iframe src="{{ route('frontend.webxr', $product->id) }}" frameborder="0" style="width: 100%"
                        height="400"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Add image popup modal -->
    <div class="modal fade" id="imagePopup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <div id="zoom-container">
                        <img src="" id="popupImage" class="img-fluid" alt="Full size image">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {

            $('.color-selector ul li > label').on('click', function(e) {
                $(".color-selector ul li > label").removeClass("active");
                $(this).addClass("active");
            });

            getVariantPrice();

            $('#add-to-cart-form input').on('change', function() {
                getVariantPrice();
            });  

            
            @if(isset($eventData))
                metaPixelEvent(@json($eventData));
            @endif

            // Image popup functionality with zoom
            $('.product-image').on('click', function() {
                var fullImage = $(this).data('full-image');
                $('#popupImage').attr('src', fullImage);
                $('#imagePopup').modal('show');
                
                // Initialize zoom after modal is shown
                $('#imagePopup').on('shown.bs.modal', function () {
                    $('#zoom-container').zoom({
                        touch: false,
                        magnify: 2,
                        onZoomIn: function() {
                            $('#zoom-container').css('cursor', 'zoom-out');
                        },
                        onZoomOut: function() {
                            $('#zoom-container').css('cursor', 'zoom-in');
                        }
                    });
                });

                // Destroy zoom when modal is hidden
                $('#imagePopup').on('hidden.bs.modal', function () {
                    $('#zoom-container').trigger('zoom.destroy');
                });
            });

            // Close modal on escape key
            $(document).keydown(function(e) {
                if (e.keyCode === 27) { // ESC key
                    $('#imagePopup').modal('hide');
                }
            });
        });

        function copy_to_clipboard(text) {
            navigator.clipboard.writeText(text);
            showToast('success', "Copied to clipboard", 'top-right')
        }

        function change_rating(rate) {
            if (rate == 1) {
                var str = '<li><i class="fa fa-star" onclick="change_rating(1)"></i></li>';
                str += '<li><i class="fa fa-star-o" onclick="change_rating(2)"></i></li>';
                str += '<li><i class="fa fa-star-o" onclick="change_rating(3)"></i></li>';
                str += '<li><i class="fa fa-star-o" onclick="change_rating(4)"></i></li>';
                str += '<li><i class="fa fa-star-o" onclick="change_rating(5)"></i></li>';
            } else if (rate == 2) {
                var str = '<li><i class="fa fa-star" onclick="change_rating(1)"></i></li>';
                str += '<li><i class="fa fa-star" onclick="change_rating(2)"></i></li>';
                str += '<li><i class="fa fa-star-o" onclick="change_rating(3)"></i></li>';
                str += '<li><i class="fa fa-star-o" onclick="change_rating(4)"></i></li>';
                str += '<li><i class="fa fa-star-o" onclick="change_rating(5)"></i></li>';
            } else if (rate == 3) {
                var str = '<li><i class="fa fa-star" onclick="change_rating(1)"></i></li>';
                str += '<li><i class="fa fa-star" onclick="change_rating(2)"></i></li>';
                str += '<li><i class="fa fa-star" onclick="change_rating(3)"></i></li>';
                str += '<li><i class="fa fa-star-o" onclick="change_rating(4)"></i></li>';
                str += '<li><i class="fa fa-star-o" onclick="change_rating(5)"></i></li>';
            } else if (rate == 4) {
                var str = '<li><i class="fa fa-star" onclick="change_rating(1)"></i></li>';
                str += '<li><i class="fa fa-star" onclick="change_rating(2)"></i></li>';
                str += '<li><i class="fa fa-star" onclick="change_rating(3)"></i></li>';
                str += '<li><i class="fa fa-star" onclick="change_rating(4)"></i></li>';
                str += '<li><i class="fa fa-star-o" onclick="change_rating(5)"></i></li>';
            } else if (rate == 5) {
                var str = '<li><i class="fa fa-star" onclick="change_rating(1)"></i></li>';
                str += '<li><i class="fa fa-star" onclick="change_rating(2)"></i></li>';
                str += '<li><i class="fa fa-star" onclick="change_rating(3)"></i></li>';
                str += '<li><i class="fa fa-star" onclick="change_rating(4)"></i></li>';
                str += '<li><i class="fa fa-star" onclick="change_rating(5)"></i></li>';
            }

            $('#user-rating-choice').html(str);
            $('#user_rate').val(rate);
        }
    </script>

    <!-- Add popup and zoom styles -->
    <style>
        .product-slick {
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        .product-slick img {
            width: 100%;
            height: auto;
            display: block;
        }
        #imagePopup .modal-content {
            background-color: transparent;
            border: none;
        }
        #imagePopup .modal-header {
            border: none;
            position: absolute;
            right: 0;
            z-index: 1;
        }
        #imagePopup .modal-header .btn-close {
            background-color: white;
            opacity: 0.8;
        }
        #imagePopup .modal-body {
            padding: 0;
            background-color: rgba(0, 0, 0, 0.9);
        }
        #zoom-container {
            position: relative;
            overflow: hidden;
            cursor: zoom-in;
        }
        #popupImage {
            max-height: 90vh;
            width: auto;
            margin: 0 auto;
            display: block;
        }
        .zoomImg {
            background-color: #fff;
            z-index: 999;
        }
        @media (max-width: 768px) {
            #popupImage {
                max-width: 100%;
                height: auto;
            }
        }
    </style>
@endsection
