
@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2> دخول المستخدمين</h2>
                            <ul>
                                <li><a href="index.html">الرئيسية</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)">{{ __('global.reset_password') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->

    <!--section start-->
    <section class="login-page section-big-py-space b-g-light">
        <div class="custom-container">
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-8 offset-xl-4 offset-lg-3 offset-md-2">
                    <div class="theme-card">
                        @if(session('message'))
                            <div class="alert alert-info" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group">
                                <label>البريد الالكتروني</label>
                                <input  id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" required autocomplete="email" autofocus placeholder="{{ __('global.login_email') }}" value="{{ old('email') }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert" >
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> 
                            <button type="submit" class="btn btn-normal">{{ __('global.send_password') }}</button> 
                        </form>

                        <a href="{{ route('login') }}" class="txt-default pt-3 d-block">تسجيل الدخول </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Section ends-->
@endsection
