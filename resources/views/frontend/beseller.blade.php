<!DOCTYPE html>
<html>

<head>
    @php
        $site_settings = get_site_setting();
    @endphp 
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('meta_title', $site_settings->site_name )</title>
    <meta name="description" content="@yield('meta_description', $site_settings->description_seo)" />
    <meta name="keywords" content="@yield('meta_keywords', $site_settings->keywords_seo)">
    <meta name="author" content="{{ $site_settings->author_seo }}">
    <meta name="sitemap_link" content="{{ $site_settings->sitemap_link_seo }}"> 

    <!--icons-->
    <link rel="icon" href="{{ $site_settings->logo ? $site_settings->logo->getUrl() : '' }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $site_settings->logo ? $site_settings->logo->getUrl() : '' }}" type="image/x-icon">

    <!--Google font-->
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">

    <!--icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/themify.css') }}">

    <!--Slick slider css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/slick-theme.css') }}">

    <!--Animate css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/animate.css') }}">
    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/bootstrap.css') }}">

    <!-- Theme css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/color3.css') }}" media="screen" id="color">

    <!-- seller css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/seller-style.css') }}" media="screen" id="color">
    <style>
        .is-invalid{
            border: 1px solid #dc3545 !important; 
        }
    </style>
</head>

<body>

    <!-- loader start -->
    <div class="loader-wrapper">
        <div>
            <img src="{{  $site_settings->logo ? $site_settings->logo->getUrl() : '' }}" alt="loader">
        </div>
    </div>
    <!-- loader end -->
    <!-- header -->
    <header class="header-area">
        <div id="" class="menu-area">
            <div class="container">
                <div class="second-menu">
                    <div class="row align-items-center">
                        <div class="col-2 col-lg-2">
                            <div class="logo">
                                <a href="{{ route('home') }}"><img src="{{  $site_settings->logo ? $site_settings->logo->getUrl() : '' }}" width="80" alt="logo"></a>
                            </div>
                        </div>

                        <div class="col-10 text-left d-xl-block" style="float: left ;  text-align: left;">
                            <div class="header-btn second-header-btn">
                                <a href="#" onclick="scrollToDivById('contact')" class="btn">سجل الان</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-end -->
    <!-- main-area -->
    <main>
        <!-- slider-area -->
        <section id="parallax" class="slider-area slider-bg2 second-slider-bg d-flex fix"
            style="background-image: url({{asset('frontend/assets/images/blue-header-bg.png')}}); background-position:left 0; background-repeat: no-repeat; background-size: 65%;">

            <div class="slider-shape ss-one layer" data-depth="0.10"><img src="{{ asset('frontend/assets/images/sellers/header-sape.png') }}"
                    alt="shape"></div>

            <div class="slider-shape ss-eight layer" data-depth="0.50"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="slider-content second-slider-content left-center">

                            <h2 data-animation="fadeInUp" data-delay=".4s">EBTEKAR STORE <span>RESELLER</span></h2>
                            <p data-animation="fadeInUp" data-delay=".6s">اول متجر الكتروني للمسوقين في مصر
                                بمنتجات اسبشيل باختيار العميل وهدايا مناسبات ومنتجات
                                دعائيه للشركات</p>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img src="{{ asset('frontend/assets/images/sellers/mobile.png') }}" alt="shape" class="s-img">
                    </div>
                </div>

            </div>
        </section>
        <!-- slider-area-end -->


        <section id="about" class="services-area services-bg pt-25 pb-20"
            style="background-image: url({{ asset('frontend/seller/img/shape/header-sape2.png') }}); background-position: right top; background-size: auto;background-repeat: no-repeat;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-10">
                        <div class="section-title text-center pl-40 pr-40 mb-45">
                            <h2>مزايا ابتكار</h2>
                            <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص
                                العربى</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-12 mb-30">
                        <div class="s-single-services text-center active">
                            <div class="services-icon">
                                <img src="{{ asset('frontend/assets/images/sellers/f-icon1.png') }}">
                            </div>
                            <div class="second-services-content">
                                <h5>منتجاتك عندنا</h5>
                                <p>
                                    المصنع الرئيسي لكل منتج هيخرج لعميلك <br />
                                    المستورد الرئيسي لكل منتج معروض عندنا
                                </p>

                                <a href="#"><span>1</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 mb-30">
                        <div class="s-single-services text-center">
                            <div class="services-icon">
                                <img src="{{ asset('frontend/assets/images/sellers/f-icon3.png') }}">
                            </div>
                            <div class="second-services-content">
                                <h5>الشحن مسؤوليتنا</h5>
                                <p>شركه الشحن الي هتشحنلك اوردراتك
                                    يعني استلام العميل مسؤليتنا احنا واي مشكله
                                    تحصل مع العميل هتتواصل معانا مباشرة
                                </p>
                                <a href="#"><span>2</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 mb-30">
                        <div class="s-single-services text-center">
                            <div class="services-icon">
                                <img src="{{ asset('frontend/assets/images/sellers/f-icon3.png') }}">
                            </div>
                            <div class="second-services-content">
                                <h5>أسعار منافسه</h5>
                                <p>منتجات بأفكار متجدده دايما لكل موسم ومختلفه عن المتواجده في السوق

                                </p>
                                <a href="#"><span>3</span></a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>



        <!--testimonial start-->
        <section class="testimonial4 section-big-py-space section-big-mt-space " style="direction: ltr;">
            <img src="{{ asset('frontend/assets/images/sellers/test-bg.jpg') }}" alt="testimonial" class="img-fluid bg-img">
            <div class="container">
                <div class="row">
                    <div class="col-12 pr-0">
                        <div class="testimonial-slide3 no-arrow"> 
                            @foreach($sellers as $seller)
                                @php
                                    $image = $seller->user->photo ? $seller->user->photo->getUrl('preview') : '';
                                @endphp
                                <div>
                                    <div class="testimonial-box">
                                        <div class="img-wrapper">
                                            <img src="{{ $image }}" alt="testimonial" class="img-fluid">
                                        </div>
                                        <div class="testimonial-detail"> 
                                            <h3>{{ $seller->user->name }}</h3>
                                            <h6>{{ $seller->qualification }}</h6> 
                                        </div>
                                    </div>
                                </div> 
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--testimonial end-->
        <!-- contact-area -->
        <section id="contact" class="section-big-mt-space contact-area contact-bg  pt-50 pb-100 p-relative fix"
            style="background-image: url({{ asset('frontend/seller/img/shape/header-sape8.png') }}); background-position: right center; background-size: auto;background-repeat: no-repeat;">
            <div class="container">

                <div class="row">
                    <div class="col-lg-6">
                        <div class="contact-img2">
                            <img src="{{ asset('frontend/assets/images/sellers/illustration.png') }}" alt="test">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="section-title mb-40">
                            <h2>سجل الأن</h2> 
                        </div>
                        @if(session('message'))
                            <div class="alert alert-warning" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if ($errors->count() > 0) 
                            <div class="alert alert-danger" style="background-color: #f8d7da;">
                                <ul class="list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('frontend.seller.register') }}" method="POST" class="contact-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="contact-field p-relative c-name mb-20" style="margin-bottom: 30px;">
                                        <input class="{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" placeholder="الاسم" name="name" value="{{old('name')}}" required>
                                        @if($errors->has('name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('name') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="contact-field p-relative c-email mb-20" style="margin-bottom: 30px;">
                                        <input class="{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" placeholder="البريد الإلكتروني" name="email" value="{{old('email')}}" required>
                                        @if($errors->has('email'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('email') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="contact-field p-relative c-subject mb-20" style="margin-bottom: 30px;">
                                        <input style="margin: 0" class="{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" type="text" placeholder="الهاتف" name="phone_number" value="{{old('phone_number')}}" required>
                                        @if($errors->has('phone_number'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('phone_number') }}
                                            </div>
                                        @endif
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="contact-field p-relative c-subject mb-20" style="margin-bottom: 30px;">
                                        <input class="{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" placeholder="كلمة المرور" name="password" required>
                                        @if($errors->has('password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="contact-field p-relative c-subject mb-20" style="margin-bottom: 30px;">
                                        <input class="{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" type="password" placeholder="تأكيد كلمة المرور" name="password_confirmation" required>
                                        @if($errors->has('password_confirmation'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password_confirmation') }}
                                            </div>
                                        @endif
                                    </div>
                                </div> 
                                <div class="col-lg-12">
                                    <div class="contact-field p-relative c-subject mb-20" style="margin-bottom: 30px;">
                                        <input class="{{ $errors->has('address') ? ' is-invalid' : '' }}" type="text" placeholder="العنوان" name="address" value="{{old('address')}}" required>
                                        @if($errors->has('address'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('address') }}
                                            </div>
                                        @endif
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="contact-field p-relative c-subject mb-20" style="margin-bottom: 30px;">
                                        <input class="{{ $errors->has('social_name') ? ' is-invalid' : '' }}" type="text" placeholder="أسم البيدج أو الجروب" name="social_name" value="{{old('social_name')}}" required>
                                        @if($errors->has('social_name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('social_name') }}
                                            </div>
                                        @endif
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="contact-field p-relative c-subject mb-20" style="margin-bottom: 30px;">
                                        <input class="{{ $errors->has('social_link') ? ' is-invalid' : '' }}" type="text" placeholder="لينك البيدج أو الجروب" name="social_link" value="{{old('social_link')}}" required>
                                        @if($errors->has('social_link'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('social_link') }}
                                            </div>
                                        @endif
                                    </div>
                                </div> 
                            </div>

                            <button type="submit" class="btn btn-solid btn-sm"> سجل الأن
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </section>
        <!-- contact-area-end -->


        <!-- footer start -->
        @include('frontend.layout.footer')
        <!-- footer end -->



        <!-- latest jquery-->
        <script src="{{ asset('frontend/assets/js/jquery-3.3.1.min.js') }}"></script>

        <!-- slick js-->
        <script src="{{ asset('frontend/assets/js/slick.js') }}"></script>



        <!-- tool tip js -->
        <script src="{{ asset('frontend/assets/js/tippy-popper.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/tippy-bundle.iife.min.js') }}"></script>

        <!-- popper js-->
        <script src="{{ asset('frontend/assets/js/popper.min.js') }}"></script>

        <!-- menu js-->
        <script src="{{ asset('frontend/assets/js/menu.js') }}"></script>

        <!-- father icon -->
        <script src="{{ asset('frontend/assets/js/feather.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/feather-icon.js') }}"></script>

        <!-- Bootstrap js-->
        <script src="{{ asset('frontend/assets/js/bootstrap.js') }}"></script>

        <!-- Bootstrap js-->
        <script src="{{ asset('frontend/assets/js/bootstrap-notify.min.js') }}"></script>

        <!-- Theme js-->
        <script src="{{ asset('frontend/assets/js/script.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/modal.js') }}"></script>
        <script>
            function scrollToDivById(divId) {
                const targetElement = $('#' + divId);
                
                if (targetElement.length) {
                    const scrollTopPosition = targetElement.offset().top;
                    $('html, body').animate({ scrollTop: scrollTopPosition }, 'slow');
                }
            }
        </script>
        @if ($errors->count() > 0)
            <script>
                scrollToDivById('contact')
            </script>
        @endif
</body>

</html>
