@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>{{ __('frontend.order_success.confirmed') }}</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">{{ __('frontend.about.home') }}</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)">   {{ __('frontend.order_success.confirmed') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->


    <!-- thank-you section start -->
    <section class="section-big-py-space light-layout">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="success-text"><i class="fa fa-check-circle" aria-hidden="true"></i>
                        <h2>{{ __('frontend.order_success.h2') }}</h2>
                        <p>{{ __('frontend.order_success.p_1') }}</p>
                        <p>{{ __('frontend.order_success.p_2') }}: {{ $order->order_num }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section ends -->
@endsection
