@extends('frontend.layout.app')

@section('content')
    <!--home slider start-->
    <section class="furniture-slide" style="direction: ltr;">
        <div class="slide-1 no-arrow">


            @foreach ($sliders as $slider)
                <a href="{{ $slider->link }}" target="_blanc">
                    <div class="slide-main">
                        <img src="{{ $slider->photo->getUrl() }}" class="img-fluid bg-img" alt="ebtekar-slider">
                        <div class="@if($loop->first) container @else custom-container @endif">
                            <div class="row">
                                <div class="col-12 p-0">

                                    <div class="slide-contain"> 
                                    </div>
                                    <div class="animat-block">

                                        @foreach ($sliders as $key => $slider)
                                            <img src="{{ $slider->photo->getUrl('preview2') }}" class="img-fluid animat1"
                                                alt="ebtekar-slider">
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    <!--home slider end-->

    <!--title start-->
    <div class="title8 section-big-pt-space">
        <h4>{{ trans('frontend.home.new_products') }}</h4>
    </div>
    <!--title end-->

    <!-- product tab start -->
    <section class="section-big-pb-space ratio_asos product" style="direction: ltr;">
        <div class="container">
            <div class="row">
                <div class="col pr-0">
                    <div class="product-slide-5 product-m no-arrow">
                        @foreach ($new_products as $product) 
                            @include('frontend.partials.single-product',['product' => $product])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product tab end -->



    <!--rounded category start-->
    <section class="rounded-category" style="direction: ltr;">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="slide-6 no-arrow">
                        @foreach ($home_categories as $home_category) 
                            <div>
                                <div class="category-contain">
                                    <div class="img-wrapper" style="border: 0; background-color: #ff000000;">
                                        <a href="{{ route('frontend.products.category',$home_category->category->slug) }}">
                                            <img src="{{ $home_category->category ? $home_category->category->banner->getUrl() : '' }}"
                                                alt="category  " class="">
                                        </a>
                                    </div>
                                    <a href="{{ route('frontend.products.category',$home_category->category->slug) }}" class="btn-rounded">
                                        {{ $home_category->category->name }}
                                    </a>
                                </div>
                            </div> 
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--rounded category end-->


    <!--tab product-->
    <section class="section-pb-space">
        <div class="tab-product-main tab-second">
            <div class="tab-prodcut-contain">
                <ul class="tabs tab-title">
                    @foreach ($freatured_categories as $key => $freatured_category) 
                        <li @if ($loop->first) class="current" @endif>
                            <a href="tab-featured-{{ $key + 1 }}">
                                <img src="{{ $freatured_category->icon ? $freatured_category->icon->getUrl('preview') : '' }}" alt="category"
                                    class="" heigh="30" width="30">
                                    &nbsp;
                                {{ $freatured_category->name }}
                            </a>
                        </li> 
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
    <!--tab product-->

    <!--media banner start-->
    <section class="section-pb-space" style="direction: ltr;">
        <div class="container">
            <div class="row ">
                <div class="col-12">
                    <div class="theme-tab">
                        <div class="tab-content-cls">
                            @foreach ($freatured_categories as $key => $featured_category) 
                                <div id="tab-featured-{{ $key + 1}}" class="tab-content @if ($loop->first) active default @endif" @if (!$loop->first) style="display:none" @endif>
                                    <div class="media-slide-5 no-arrow">
                                        @foreach ($featured_category->products->take(21)->chunk(3) as $chunk)
                                            <div>
                                                <div class="media-banner b-g-white1 border-0">
                                                    @foreach ($chunk as $product)  
                                                        @php
                                                            $product_image = isset($product->photos[0]) ? $product->photos[0]->getUrl('preview2') : ''; 
                                                        @endphp 
                                                        <div class="media-banner-box">
                                                            <div class="media">
                                                                <a href="{{ route('frontend.product', $product->slug) }}">
                                                                    <img src="{{ $product_image }}" class="img-fluid  " style="width: 86px;height:110px" alt="banner">
                                                                </a>
                                                                <div class="media-body">
                                                                    <div class="media-contant">
                                                                        <div>
                                                                            <div class="product-detail">
                                                                                <ul class="rating">
                                                                                    @include('frontend.partials.rate',['rate' => $product->rating])
                                                                                </ul>
                                                                                <a
                                                                                    href="{{ route('frontend.product', $product->slug) }}">
                                                                                    <p>{{ $product->name }}</p>
                                                                                </a>
                                                                                <h6>
                                                                                    <?php echo $product->calc_price_as_text(); ?>  
                                                                                    @if(auth()->check() && auth()->user()->user_type == 'seller')
                                                                                        <br>
                                                                                        <button class="btn btn-outline-warning btn-sm"> الربح : {{ front_calc_commission_currency($product->unit_price,$product->purchase_price)['value'] }} </button> 
                                                                                    @endif
                                                                                </h6>
                                                                            </div>
                                                                            <div class="cart-info">
                                                                                @if($product->variant_product || $product->special)
                                                                                    <a href="{{ route('frontend.product', $product->slug) }}" class="tooltip-top add-cartnoty" data-tippy-content="Add to cart">
                                                                                        <i data-feather="shopping-cart"></i>
                                                                                    </a>
                                                                                @else  
                                                                                    <form action="{{route('frontend.cart.add')}}" method="POST" enctype="multipart/form-data" style="margin-left: 7px;display:inline">
                                                                                        @csrf
                                                                                        <input type="hidden" name="id" value="{{$product->id}}">
                                                                                        <input type="hidden" name="variant">
                                                                                        <button type="submit" class="tooltip-top add-cartnoty" data-tippy-content="Add to cart">
                                                                                            <i data-feather="shopping-cart"></i>
                                                                                        </button>
                                                                                    </form>
                                                                                @endif
                                                                                <a href="{{ route('frontend.wishlist.add', $product->slug) }}"
                                                                                    class="add-to-wish tooltip-top"
                                                                                    data-tippy-content="Add to Wishlist"><i
                                                                                        data-feather="heart"
                                                                                        class="add-to-wish"></i></a>
                                                                                <a href="javascript:void(0)" onclick="quick_view('{{$product->id}}')" data-bs-toggle="modal" data-bs-target="#quick-view" class="tooltip-top"  data-tippy-content="Quick View"><i  data-feather="eye"></i></a>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div> 
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--media banner end-->

    <!--collection banner start-->
    <section class="collection-banner layout-3">
        <div class="container">
            <div class="row layout-3-collection">
                @foreach ($banners_1 as $banner)
                    <div class="col-md-6 ">
                        <div class="collection-banner-main banner-12 banner-style3 text-center p-right">
                            <div class="collection-img" >
                                <img src="{{ $banner->photo->getUrl() }}" class="img-fluid bg-img " alt="banner">
                            </div>
                            <div class="collection-banner-contain  ">
                                <div>
                                    {{-- <h3>وصل حديثا</h3>
                                <h4>اجندة 2023</h4> --}}
                                    <a href="{{ $banner->url }}" class="btn btn-rounded btn-xs">اطلب الان </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--collection banner end-->


    <!--title start-->
    <div class="title8 section-big-pt-space">
        <h4>{{ trans('frontend.home.most_selling') }}</h4>
    </div>
    <!--title end-->


    <!-- product section start -->
    <section class="section-big-pb-space ratio_square product" style="direction: ltr;">
        <div class="container">
            <div class="row">
                <div class="col pr-0">
                    <div class="product-slide-5 product-m no-arrow">
                        @foreach ($best_selling_products as $product) 
                            @include('frontend.partials.single-product',['product' => $product])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product section end -->
@endsection
