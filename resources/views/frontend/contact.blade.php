@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>تواصل معنا</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)">تواصل معنا</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="contact-page section-big-py-space b-g-light">
        <div class="custom-container">
            <div class="row section-big-pb-space">
                <div class="col-xl-6 offset-xl-3">
                    <form class="theme-form" action="{{ route('frontend.contact.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">الاسم الاول</label>
                                    <input type="text" class="form-control" id="first_name" placeholder="الاسم" name="first_name" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">الاسم الاخير</label>
                                    <input type="text" class="form-control" id="last_name" placeholder="الاسم الاخير" name="last_name" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number">الهاتف</label>
                                    <input type="text" class="form-control" id="phone_number" placeholder="الهاتف" name="phone_number" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">البريد الالكتروني</label>
                                    <input type="email" class="form-control" placeholder="البريد الالكتروني" name="email" required="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div>
                                    <label for="message">اكتب رسالتك هنا</label>
                                    <textarea class="form-control" placeholder="اكتب رسالتك هنا" rows="2" name="message"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-normal" type="submit"> ارسال</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </section>
    <!--Section ends-->
@endsection
