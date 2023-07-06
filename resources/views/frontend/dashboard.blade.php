@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2> حسابي</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="{{ route('frontend.dashboard') }}"> لوحة التحكم الخاصة بي </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->


    <!-- section start -->
    <section class="section-big-py-space b-g-light">
        <div class="container">
            <div class="row">

                <!-- front_navbar start-->
                    @include('frontend.partials.dashboard_navbar')
                <!-- front_navbar End -->

                <div class="col-lg-5">
                    <div class="dashboard-right">
                        <div class="dashboard">
                            <div class="page-title">
                                <h2>حسابي</h2>
                            </div>
                            <div class="box-account box-info">
                                <form class="theme-form" action="{{ route('frontend.update_profile') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label>البريد الالكتروني</label>
                                        <input type="email" class="form-control" placeholder="البريد الالكتروني" value="{{ $user->email }}"  required="" name="email" disabled>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>الأسم</label>
                                        <input type="text" class="form-control" placeholder="الأسم" value="{{ $user->name }}"  required="" name="name">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>رقم التليفون</label>
                                        <input type="text" class="form-control" placeholder="رقم التليفون" value="{{ $user->phone_number }}"  required="" name="phone_number">
                                        @error('phone_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>عنوان</label>
                                        <input type="text" class="form-control" placeholder="عنوان" value="{{ $user->address }}"  required="" name="address">
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>صورة البروفايل</label>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <input type="file" class="form-control"  name="photo">
                                            </div>
                                            <div class="col-md-3">
                                                <img src="{{ $user->photo ? $user->photo->getUrl('thumb') : "" }}" alt="">
                                            </div>
                                        </div>
                                        @error('photo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    @if(auth()->user()->user_type == 'seller') 
                                        <div class="form-group">
                                            <label>المؤهل</label>
                                            <input type="text" class="form-control" placeholder="المؤهل" value="{{ $user->seller->qualification }}"  required="" name="qualification">
                                            @error('qualification')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>أسم البيدج أو الجروب</label>
                                            <input type="text" class="form-control" placeholder="رقم التليفون" value="{{ $user->seller->social_name }}"  required="" name="social_name">
                                            @error('social_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>لينك البيدج أو الجروب</label>
                                            <input type="text" class="form-control" placeholder="رقم التليفون" value="{{ $user->seller->social_link }}"  required="" name="social_link">
                                            @error('social_link')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>صورة البطاقة من الخلف</label>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <input type="file" class="form-control"  name="identity_back">
                                                </div>
                                                <div class="col-md-3">
                                                    <img src="{{ isset($user->seller->identity_back) ? $user->seller->identity_back->getUrl('thumb') : "" }}" alt="">
                                                </div>
                                            </div>
                                            @error('identity_back')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>صورة البطاقة من الأمام</label>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <input type="file" class="form-control"  name="identity_front">
                                                </div>
                                                <div class="col-md-3">
                                                    <img src="{{ isset($user->seller->identity_front) ? $user->seller->identity_front->getUrl('thumb') : "" }}" alt="">
                                                </div>
                                            </div>
                                            @error('identity_front')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-normal">تحديث</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="dashboard-right">
                        <div class="dashboard">
                            <div class="page-title">
                                <h2>تحديث كلمة المرور </h2>
                            </div>
                            <div class="box-account box-info">

                                <form class="theme-form" action="{{ route('frontend.update_password') }}" method="POST">
                                    @csrf
                                    @if($user->password != null)
                                        <div class="form-group">
                                            <label>كلمة المرور الحالية </label>
                                            <input type="password" class="form-control" required="" name="old_password">
                                            @error('old_password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label>كلمة مرور جديدة </label>
                                        <input type="password" class="form-control" required="" name="password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>   تأكيد كلمة المرور</label>
                                        <input type="password" class="form-control" required="" name="password_confirmation">
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-normal">تحديث</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- section end -->
@endsection
