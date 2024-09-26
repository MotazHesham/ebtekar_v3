@extends('frontend.layout.app')

@section('content')

    @if ($errors->count() > 0)
        <div class="alert alert-danger" style="background-color: #f8d7da;">
            <ul class="list-unstyled">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>{{ __('frontend.contact_us.contact') }}</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">{{ __('frontend.about.home') }}</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)">{{ __('frontend.contact_us.contact') }}</a></li>
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
                                    <label for="first_name"> {{ __('frontend.contact_us.first_name') }}  </label>
                                    <input type="text" class="form-control" id="first_name" placeholder="{{ __('frontend.contact_us.first_name') }}" name="first_name" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">  {{ __('frontend.contact_us.last_name') }} </label>
                                    <input type="text" class="form-control" id="last_name" placeholder="   {{ __('frontend.contact_us.last_name') }}" name="last_name" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number"> {{ __('frontend.contact_us.phone_number') }}</label>
                                    <input type="text" class="form-control" id="phone_number" placeholder="{{ __('frontend.contact_us.phone_number') }}" name="phone_number" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">   {{ __('frontend.contact_us.email') }}</label>
                                    <input type="email" class="form-control" placeholder="   {{ __('frontend.contact_us.email') }}" name="email" required="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div>
                                    <label for="message">  {{ __('frontend.contact_us.message') }}   </label>
                                    <textarea class="form-control" placeholder="     {{ __('frontend.contact_us.message') }}" rows="2" name="message"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-normal" type="submit">  {{ __('frontend.contact_us.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </section>
    <!--Section ends-->
@endsection
