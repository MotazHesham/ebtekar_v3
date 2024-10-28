@extends('frontend.layout.app')

@section('content')
    <!--home slider start-->
    <section class="furniture-slide" style="direction: ltr;">
        <div class="slide-1 no-arrow"> 
            @foreach ($sliders as $slider)
                <a href="{{ $slider->link }}" target="_blanc">
                    <div class="slide-main">
                        <img loading="lazy" src="{{ $slider->photo->getUrl('preview2') }}" class="img-fluid bg-img" alt="ebtekar-slider" style="width: 100%"> 
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    <!--home slider end-->

    <!--title start-->
    <div class="title8 section-big-pt-space">
        <h4>{{ __('frontend.home.new_products') }}</h4>
    </div>
    <!--title end-->

    <!-- product tab start -->
    <section class="section-big-pb-space ratio_asos product" style="direction: ltr;">
        <div class="container">
            <div class="row">
                <div class="col pr-0">
                    <div class="product-slide-5 product-m no-arrow">
                        @foreach ($new_products as $product) 
                            @include('frontend.partials.single-product',['product' => $product, 'preview' => 'preview2'])
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
                                            <img loading="lazy" src="{{ $home_category->category ? $home_category->category->banner->getUrl() : '' }}"
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
                                <img loading="lazy" src="{{ $freatured_category->icon ? $freatured_category->icon->getUrl('preview') : '' }}" alt="category"
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

    @foreach ($freatured_categories as $key => $featured_category) 
        <div id="tab-featured-{{ $key + 1}}" class="tab-content @if ($loop->first) active default @endif" @if (!$loop->first) style="display:none" @endif>
            <section class="section-big-pb-space ratio_asos product" style="direction: ltr;">
                <div class="container">
                    <div class="row">
                        <div class="col pr-0">
                            <div class="product-slide-5 product-m no-arrow">
                                @foreach ($featured_category->products->take(8) as $product) 
                                    @include('frontend.partials.single-product',['product' => $product, 'preview' => 'preview2'])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div> 
    @endforeach 

    <!--collection banner start-->
    <section class="collection-banner layout-3">
        <div class="container">
            <div class="row layout-3-collection">
                @foreach ($banners_1 as $banner)
                    <div class="col-md-6 ">
                        <div class="collection-banner-main banner-12 banner-style3 text-center p-right">
                            <div class="collection-img" >
                                <img loading="lazy" src="{{ $banner->photo->getUrl() }}" class="img-fluid bg-img " alt="banner">
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
        <h4>{{ __('frontend.home.most_selling') }}</h4>
    </div>
    <!--title end-->


    <!-- product section start -->
    <section class="section-big-pb-space ratio_square product" style="direction: ltr;">
        <div class="container">
            <div class="row">
                <div class="col pr-0">
                    <div class="product-slide-5 product-m no-arrow">
                        @foreach ($best_selling_products as $product) 
                            @include('frontend.partials.single-product',['product' => $product, 'preview' => 'preview2'])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product section end -->
@endsection
