<!DOCTYPE html>
<html>

<head>
    <title>Bigdeal - Multi-purpopse E-commerce Html Template</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">

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
</head>

<body>

    <!-- loader start -->
    <div class="loader-wrapper">
        <div>
            <img src="{{ asset('frontend/assets/images/loader.gif') }}" alt="loader">
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
                                <a href="seller.html"><img src="{{ asset('frontend/assets/images/logo.png') }}" width="130"
                                        alt="logo"></a>
                            </div>
                        </div>

                        <div class="col-10 text-left d-xl-block" style="float: left ;  text-align: left;">
                            <div class="header-btn second-header-btn">
                                <a href="register.html" class="btn">سجل الان</a>
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
                            <div>
                                <div class="testimonial-box">
                                    <div class="img-wrapper">
                                        <img src="{{ asset('frontend/assets/images/testimonial/1.jpg') }}" alt="testimonial"
                                            class="img-fluid">
                                    </div>
                                    <div class="testimonial-detail">
                                        <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من
                                            مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى
                                            إضافة إلى زيادة عدد الحروف التى يولدها التطبيق.</p>
                                        <h3>محمد احمد</h3>
                                        <h6>مدير عام</h6>

                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="testimonial-box">
                                    <div class="img-wrapper">
                                        <img src="{{ asset('frontend/assets/images/testimonial/4.jpg') }}" alt="testimonial"
                                            class="img-fluid">
                                    </div>
                                    <div class="testimonial-detail">
                                        <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من
                                            مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى
                                            إضافة إلى زيادة عدد الحروف التى يولدها التطبيق.</p>
                                        <h3>محمد احمد</h3>
                                        <h6>مدير عام</h6>

                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="testimonial-box">
                                    <div class="img-wrapper">
                                        <img src="{{ asset('frontend/assets/images/testimonial/3.jpg') }}" alt="testimonial"
                                            class="img-fluid">
                                    </div>
                                    <div class="testimonial-detail">
                                        <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من
                                            مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى
                                            إضافة إلى زيادة عدد الحروف التى يولدها التطبيق.</p>
                                        <h3>محمد احمد</h3>
                                        <h6>مدير عام</h6>

                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="testimonial-box">
                                    <div class="img-wrapper">
                                        <img src="{{ asset('frontend/assets/images/testimonial/2.jpg') }}" alt="testimonial"
                                            class="img-fluid">
                                    </div>
                                    <div class="testimonial-detail">
                                        <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من
                                            مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى
                                            إضافة إلى زيادة عدد الحروف التى يولدها التطبيق.</p>
                                        <h3>محمد احمد</h3>
                                        <h6>مدير عام</h6>

                                    </div>
                                </div>
                            </div>
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
                            <h2>تواصل معنا</h2>
                            <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص
                                العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد
                                الحروف التى يولدها التطبيق.</p>
                        </div>
                        <form action="#" class="contact-form">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="contact-field p-relative c-name mb-20">
                                        <input type="text" placeholder="الاسم">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="contact-field p-relative c-email mb-20">
                                        <input type="text" placeholder="البريد الإلكتروني">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="contact-field p-relative c-subject mb-20">
                                        <input type="text" placeholder="الهاتف">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="contact-field p-relative c-message mb-45">
                                        <textarea name="message" id="message" cols="10" rows="10" placeholder="اكتب تعليقك"></textarea>
                                    </div>
                                    <button class="btn">إرسال </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>

        </section>
        <!-- contact-area-end -->


        <!-- footer start -->
        <footer>
            <div class="footer1 ">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="footer-main">
                                <div class="footer-box">
                                    <div class="footer-title mobile-title">
                                        <h5>عن ابتكار</h5>
                                    </div>
                                    <div class="footer-contant">
                                        <div class="footer-logo">
                                            <a href="index.html">
                                                <img src="{{ asset('frontend/assets/images/logo_gray.png') }}" class="img-fluid"
                                                    alt="logo">
                                            </a>
                                        </div>
                                        <p>% ابتكار شركه شبابيه مصريه<br>
                                            بنقدم خدمات طباعه بجميع انواعها<br>
                                            انتظروا دايما كل جديد</p>
                                        <ul class="sosiyal">
                                            <li><a href="javascript:void(0)"><i class="fa fa-facebook"></i></a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-instagram"></i></a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-youtube"></i></a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-whatsapp"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="footer-box">
                                    <div class="footer-title">
                                        <h5>حسابي</h5>
                                    </div>
                                    <div class="footer-contant">
                                        <ul>
                                            <li><a href="index.html">الرئيسية</a></li>
                                            <li><a href="about.html">عن إبتكار</a></li>
                                            <li><a href="support.html">سياسة الدعم</a></li>
                                            <li><a href="return.html">سياسة المرتجعات</a></li>
                                            <li><a href="sellerpolicy.html">سياسة البائع</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="footer-box">
                                    <div class="footer-title">
                                        <h5>تواصل معنا</h5>
                                    </div>
                                    <div class="footer-contant">
                                        <ul class="contact-list">
                                            <li><i class="fa fa-map-marker"></i>وسط البلد ، القاهرة ،
                                                <br> جمهورية مصر العربية<span></span>
                                            </li>
                                            <li><i class="fa fa-phone"></i>تليفون: <span>01000586206</span></li>
                                            <li><i class="fa fa-envelope-o"></i>بريد الكتروني: support@ebtekarstore.net
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="footer-box">
                                    <div class="footer-title">
                                        <h5>القائمة البريدية</h5>
                                    </div>
                                    <div class="footer-contant">
                                        <div class="newsletter-second">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="الاسم بالكامل">
                                                    <span class="input-group-text"><i class="ti-user"></i></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="البريد الإلكتروني">
                                                    <span class="input-group-text"><i class="ti-email"></i></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <a href="javascript:void(0)" class="btn btn-solid btn-sm">اشترك الان
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="subfooter footer-border">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="footer-left">
                                <p>© 2023 Ebtekar Store</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </footer>
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
</body>

</html>
