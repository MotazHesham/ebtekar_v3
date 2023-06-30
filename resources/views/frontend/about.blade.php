@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>عن ابتكار</h2>
                            <ul>
                                <li><a href="{{route('home')}}">الرئيسية</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)">عن ابتكار</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- about section start -->
    <section class="about-page section-big-py-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="banner-section"><img src="{{ asset('frontend/assets/images/blog/1.jpg') }}" class="img-fluid   w-100" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <p class="mb-2">{{ $general_settings->description }}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- about section end -->

    <!--testimonial start-->
    <section class="testimonial testimonial-inverse" style="direction: ltr;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="slide-1 no-arrow">
                        @foreach($general_settings->photos as $key => $media)
                            <div>
                                <div class="testimonial-contain">
                                    <div class="media">
                                        <img src="{{ $media->getUrl() }}" class="img-fluid"
                                            alt="testimonial">
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
@endsection
