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
                                    <a href="{{ route('home') }}">
                                        <img src="{{ $home_general_setting->logo->getUrl() }}" class="img-fluid"
                                            alt="logo">
                                    </a>
                                </div>
                                <p>
                                    {{ $home_general_setting->description }}
                                </p>
                                <ul class="sosiyal">
                                    <li><a href="{{ $home_general_setting->facebook }}"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="{{ $home_general_setting->instagram }}"><i class="fa fa-instagram"></i></a></li>
                                    <li><a href="{{ $home_general_setting->youtube }}"><i class="fa fa-youtube"></i></a></li>
                                </ul>
                                <ul class="sosiyal">
                                    <li><a href="{{ $home_general_setting->whatsapp }}"><i class="fa fa-whatsapp"></i></a></li>
                                    <li><a href="{{ $home_general_setting->telegram }}"><i class="fa fa-telegram"></i></a></li>
                                    <li><a href="{{ $home_general_setting->linkedin }}"><i class="fa fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="footer-box">
                            <div class="footer-title">
                                <h5>حسابي</h5>
                            </div>
                            <div class="footer-contant">
                                <ul>
                                    <li><a href="{{ route('home') }}">الرئيسية</a></li>
                                    <li><a href="{{ route('frontend.about') }}">عن إبتكار</a></li>
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
                                    <li><i class="fa fa-map-marker"></i>
                                        {{ $home_general_setting->address }}
                                    </li>
                                    <li><i class="fa fa-phone"></i>تليفون: <span>{{ $home_general_setting->phone_number}}</span></li>
                                    <li><i class="fa fa-envelope-o"></i>بريد الكتروني: {{ $home_general_setting->email }}
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
                                    <form action="{{ route('frontend.subscribe') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="الاسم بالكامل">
                                                <span class="input-group-text"><i class="ti-user"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="email" name="email" class="form-control"
                                                    placeholder="البريد الإلكتروني">
                                                <span class="input-group-text"><i class="ti-email"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-solid btn-sm">اشترك الان
                                            </button>
                                        </div>
                                    </form>
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
                        <p>© {{ date('Y') }} {{ $home_general_setting->site_name }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</footer>
