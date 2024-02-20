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
                                        <img src="{{ $site_settings->logo ? $site_settings->logo->getUrl() : '' }}" class="img-fluid"
                                            alt="logo" style="height: 115px">
                                    </a>
                                </div>
                                <p>
                                    {{ $site_settings->description }}
                                </p>
                                <ul class="sosiyal">
                                    <li><a href="{{ $site_settings->facebook }}"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="{{ $site_settings->instagram }}"><i class="fa fa-instagram"></i></a></li>
                                    <li><a href="{{ $site_settings->youtube }}"><i class="fa fa-youtube"></i></a></li>
                                </ul>
                                <ul class="sosiyal">
                                    <li><a href="{{ $site_settings->whatsapp }}"><i class="fa fa-whatsapp"></i></a></li>
                                    <li><a href="{{ $site_settings->telegram }}"><i class="fa fa-telegram"></i></a></li>
                                    <li><a href="{{ $site_settings->linkedin }}"><i class="fa fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="footer-box">
                            <div class="footer-title">
                                <h5>{{ trans('frontend.footer.profile') }}</h5>
                            </div>
                            <div class="footer-contant">
                                <ul>
                                    <li><a href="{{ route('home') }}">{{ trans('frontend.footer.home') }}</a></li>
                                    <li>
                                        <a href="{{ route('frontend.about') }}">   
                                            @if($site_settings->id  == 2)
                                                {{ trans('frontend.about.ertgal') }}
                                            @elseif($site_settings->id  == 3)
                                                {{ trans('frontend.about.figures') }}
                                            @elseif($site_settings->id  == 4)
                                                {{ trans('frontend.about.shirti') }}
                                            @else
                                                {{ trans('frontend.about.ebtekar') }}
                                            @endif
                                        </a>
                                    </li>
                                    <li><a href="{{ route('frontend.policies','support') }}">{{ trans('frontend.footer.support') }} </a></li>
                                    <li><a href="{{ route('frontend.policies','return') }}">{{ trans('frontend.footer.returned') }} </a></li>
                                    <li><a href="{{ route('frontend.policies','terms') }}">{{ trans('frontend.footer.terms') }} </a></li>
                                    <li><a href="{{ route('frontend.policies','seller') }}">{{ trans('frontend.footer.seller') }} </a></li> 
                                    <li><a href="{{ route('frontend.policies','privacy') }}">{{ trans('frontend.footer.privacy') }} </a></li> 
                                </ul>
                            </div>
                        </div>
                        <div class="footer-box">
                            <div class="footer-title">
                                <h5> {{ trans('frontend.footer.contact_us') }}  </h5>
                            </div>
                            <div class="footer-contant">
                                <ul class="contact-list">
                                    <li><i class="fa fa-map-marker"></i>
                                        {{ $site_settings->address }}
                                    </li>
                                    <li><i class="fa fa-phone"></i>{{ trans('frontend.footer.phone') }}: <span> <a class="btn-link" href="tel:{{ $site_settings->phone_number}}">{{ $site_settings->phone_number}}</a> </span></li>
                                    <li><i class="fa fa-envelope-o"></i> {{ trans('frontend.footer.email') }}: <a class="btn-link" href="mailto:{{ $site_settings->email }}">{{ $site_settings->email }}</a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="footer-box">
                            <div class="footer-title">
                                <h5>{{ trans('frontend.footer.subscribe') }} </h5>
                            </div>
                            <div class="footer-contant">
                                <div class="newsletter-second">
                                    <form action="{{ route('frontend.subscribe') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="{{ trans('frontend.footer.name') }}">
                                                <span class="input-group-text"><i class="ti-user"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="email" name="email" class="form-control"
                                                    placeholder="{{ trans('frontend.footer.email') }}">
                                                <span class="input-group-text"><i class="ti-email"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-solid btn-sm"> 
                                                {{ trans('frontend.footer.subscribe_now') }}
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
                        <p>© {{ date('Y') }} {{ $site_settings->site_name }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</footer>
