@extends('frontend.layout.app')
@section('styles') 
    @php
        $firstSliderImage = $sliders->first() && $sliders->first()->photo ? $sliders->first()->photo->getUrl() : null;
    @endphp
    <link rel="preload" as="image" href="{{ $firstSliderImage }}" fetchpriority="high">
    <style>
        .google-reviews {
            background-color: #f8f9fa;
            padding: 40px 0;
        }
        .review-item {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .review-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .reviewer-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .reviewer-info h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }
        .stars {
            color: #ffc107;
            margin: 5px 0;
        }
        .review-date {
            color: #6c757d;
            font-size: 12px;
        }
        .review-text {
            color: #495057;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }
        .reviews-slider .slick-dots {
            bottom: -30px;
        }
        .reviews-slider .slick-dots li button:before {
            font-size: 10px;
        }
    </style>
@endsection
@section('content')
    <!--home slider start-->
    <section class="furniture-slide" style="direction: ltr;">
        <div class="slide-1 no-arrow">
            @foreach ($sliders as $slider)
                <a href="{{ $slider->link }}" target="_blanc">
                    <div class="slide-main">
                        <img src="{{ $slider->photo->getUrl() }}" class="img-fluid bg-img" alt="ebtekar-slider" 
                            style="width: 100%" width="1200" height="600" onerror="this.onerror=null;this.src='{{ asset('placeholder.jpg') }}';">
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
                                            <img loading="lazy"  src="{{ $home_category->category ? $home_category->category->banner->getUrl() : '' }}"
                                                alt="category  " class=""  onerror="this.onerror=null;this.src='{{ asset('placeholder.jpg') }}';">
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
                                <img loading="lazy"  src="{{ $freatured_category->icon ? $freatured_category->icon->getUrl('preview') : '' }}" alt="category"
                                class="" heigh="30" width="30"  onerror="this.onerror=null;this.src='{{ asset('placeholder.jpg') }}';">
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
                                <img loading="lazy"  src="{{ $banner->photo->getUrl() }}" class="img-fluid bg-img " alt="banner">
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

    <!-- google reviews start -->
    {{-- <section class="google-reviews section-pb-space">
        <div class="container">
            <div class="title8">
                <h4>{{ __('frontend.home.customer_reviews') }}</h4>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="google-reviews-container" class="reviews-slider">
                        <!-- Reviews will be loaded here via JavaScript -->
                        <div class="text-center">
                            <div class="spinner-border spinner-border-sm text-dark" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- google reviews end -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            new_products();
            best_selling();
            getfeturedProducts("{{$first_featured_category_id}}");
            loadGoogleReviews();
        })

        function loadGoogleReviews() {
            // Replace YOUR_PLACE_ID with your actual Google Place ID
            const placeId = 'YOUR_PLACE_ID';
            const apiKey = 'YOUR_GOOGLE_API_KEY';
            
            $.ajax({
                url: `https://maps.googleapis.com/maps/api/place/details/json?place_id=${placeId}&fields=reviews&key=${apiKey}`,
                method: 'GET',
                success: function(response) {
                    if (response.result && response.result.reviews) {
                        const reviews = response.result.reviews;
                        let reviewsHtml = '';
                        
                        reviews.forEach(review => {
                            const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
                            const date = new Date(review.time * 1000).toLocaleDateString();
                            
                            reviewsHtml += `
                                <div class="review-item">
                                    <div class="review-header">
                                        <img src="${review.profile_photo_url}" alt="${review.author_name}" class="reviewer-photo">
                                        <div class="reviewer-info">
                                            <h5>${review.author_name}</h5>
                                            <div class="stars">${stars}</div>
                                            <span class="review-date">${date}</span>
                                        </div>
                                    </div>
                                    <p class="review-text">${review.text}</p>
                                </div>
                            `;
                        });
                        
                        $('#google-reviews-container').html(reviewsHtml);
                        
                        // Initialize slick slider for reviews
                        $('.reviews-slider').slick({
                            dots: true,
                            infinite: true,
                            speed: 300,
                            slidesToShow: 3,
                            slidesToScroll: 1,
                            responsive: [
                                {
                                    breakpoint: 991,
                                    settings: {
                                        slidesToShow: 2,
                                        slidesToScroll: 1
                                    }
                                },
                                {
                                    breakpoint: 576,
                                    settings: {
                                        slidesToShow: 1,
                                        slidesToScroll: 1
                                    }
                                }
                            ]
                        });
                    }
                },
                error: function(error) {
                    console.error('Error loading Google Reviews:', error);
                    $('#google-reviews-container').html('<p class="text-center">Unable to load reviews at this time.</p>');
                }
            });
        }

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
