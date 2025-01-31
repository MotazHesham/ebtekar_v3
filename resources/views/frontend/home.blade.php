@extends('frontend.layout.app')

@section('content')
    <!--home slider start-->
    <section class="furniture-slide" style="direction: ltr;">
        <div class="slide-1 no-arrow">
            @foreach ($sliders as $slider)
                <a href="{{ $slider->link }}" target="_blanc">
                    <div class="slide-main">
                        <img src="{{ $slider->photo->getUrl('preview2') }}" class="img-fluid bg-img" alt="ebtekar-slider"
                            style="width: 100%">
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
    <div class="text-center" id="new-products-section">
        <div class="spinner-border spinner-border-sm text-dark" role="status"></div>
    </div>
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
                                        <a href="{{ route('frontend.products.category', $home_category->category->slug) }}">
                                            <img src="{{ $home_category->category ? $home_category->category->banner->getUrl() : '' }}"
                                                alt="category  " class="">
                                        </a>
                                    </div>
                                    <a href="{{ route('frontend.products.category', $home_category->category->slug) }}"
                                        class="btn-rounded">
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

    <section class="section-pb-space">
        <div class="tab-product-main tab-second">
            <div class="tab-prodcut-contain">
                <ul class="tabs tab-title"> 
                    @foreach ($featured_categories as $key => $freatured_category) 
                        <li> 
                            <span onclick="getfeturedProducts('{{$freatured_category->id}}')" style="cursor: pointer"> 
                                <img src="{{ $freatured_category->icon ? $freatured_category->icon->getUrl('preview') : '' }}" alt="category"
                                class="" heigh="30" width="30">
                                &nbsp;
                                {{ $freatured_category->name }} 
                            </span>
                        </li> 
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <div class="text-center" id="fetured-categories-section">
        {{-- product of featured categories --}}
        <div class="spinner-border spinner-border-sm text-dark" role="status"></div>
    </div>

    <!--collection banner start-->
    <section class="collection-banner layout-3">
        <div class="container">
            <div class="row layout-3-collection">
                @foreach ($banners_1 as $banner)
                    <div class="col-md-6 ">
                        <div class="collection-banner-main banner-12 banner-style3 text-center p-right">
                            <div class="collection-img">
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
        <h4>{{ __('frontend.home.most_selling') }}</h4>
    </div>
    <!--title end-->


    <!-- product section start -->
    <div class="text-center" id="best-selling-section">
        <div class="spinner-border spinner-border-sm text-dark" role="status"></div>
    </div>
    <!-- product section end -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            new_products();
            best_selling();
            getfeturedProducts("{{$first_featured_category_id}}");
        })
        function new_products() {
            $.ajax({
                type: 'POST',
                url: '{{ route('frontend.sections.new_products') }}',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $("#new-products-section").html(data);
                    feather.replace(); // Reinitialize Feather icon
                    $(".product-slide-5").slick({
                        arrows: true,
                        dots: false,
                        infinite: false,
                        speed: 300,
                        slidesToShow: 5,
                        slidesToScroll: 1,
                        responsive: [{
                                breakpoint: 1700,
                                settings: {
                                    slidesToShow: 5,
                                    slidesToScroll: 5,
                                    infinite: true
                                }
                            },
                            {
                                breakpoint: 1200,
                                settings: {
                                    slidesToShow: 4,
                                    slidesToScroll: 4,
                                    infinite: true
                                }
                            },
                            {
                                breakpoint: 991,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 3,
                                    infinite: true
                                }
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2
                                }
                            }
                        ]
                    });
                }
            });
        }

        function getfeturedProducts(category_id) {
            $("#fetured-categories-section").html('<div class="spinner-border spinner-border-sm text-dark" role="status"></div>');
            $.ajax({
                type: 'POST',
                url: '{{ route('frontend.sections.featured_categories') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: category_id
                },
                success: function(data) {
                    $("#fetured-categories-section").html(data);
                    feather.replace(); // Reinitialize Feather icon
                    $(".product-slide-6").slick({
                        arrows: true,
                        dots: false,
                        infinite: false,
                        speed: 300,
                        slidesToShow: 5,
                        slidesToScroll: 1,
                        responsive: [{
                                breakpoint: 1700,
                                settings: {
                                    slidesToShow: 5,
                                    slidesToScroll: 5,
                                    infinite: true
                                }
                            },
                            {
                                breakpoint: 1200,
                                settings: {
                                    slidesToShow: 4,
                                    slidesToScroll: 4,
                                    infinite: true
                                }
                            },
                            {
                                breakpoint: 991,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 3,
                                    infinite: true
                                }
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2
                                }
                            }
                        ]
                    });
                }
            });
        }

        function best_selling() {
            $.ajax({
                type: 'POST',
                url: '{{ route('frontend.sections.best_selling_products') }}',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $("#best-selling-section").html(data);
                    feather.replace(); // Reinitialize Feather icon
                    $(".product-slide-7").slick({
                        arrows: true,
                        dots: false,
                        infinite: false,
                        speed: 300,
                        slidesToShow: 5,
                        slidesToScroll: 1,
                        responsive: [{
                                breakpoint: 1700,
                                settings: {
                                    slidesToShow: 5,
                                    slidesToScroll: 5,
                                    infinite: true
                                }
                            },
                            {
                                breakpoint: 1200,
                                settings: {
                                    slidesToShow: 4,
                                    slidesToScroll: 4,
                                    infinite: true
                                }
                            },
                            {
                                breakpoint: 991,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 3,
                                    infinite: true
                                }
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2
                                }
                            }
                        ]
                    });
                }
            });
        }
    </script>
@endsection
