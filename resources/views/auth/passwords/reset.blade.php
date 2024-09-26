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
                            <li><a href="{{ route('home') }}">الرئيسية</a></li>
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
                    <form method="POST" action="{{ route('password.request') }}">
                        @csrf
                    
                        <input name="token" value="{{ $token }}" type="hidden">
                    
                        <div class="form-group">
                            <input id="email" type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="{{ __('global.login_email') }}" value="{{ $email ?? old('email') }}">
                    
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <input id="password" type="password" name="password" class="form-control" required placeholder="{{ __('global.login_password') }}">
                    
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <input id="password-confirm" type="password" name="password_confirmation" class="form-control" required placeholder="{{ __('global.login_password_confirmation') }}">
                        </div>
                    
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-normal">
                                    {{ __('global.reset_password') }}
                                </button>
                            </div>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</section>
<!--Section ends-->
@endsection