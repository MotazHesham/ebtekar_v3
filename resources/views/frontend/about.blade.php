@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>
                                @if($site_settings->id  == 2)
                                    {{ trans('frontend.about.ertgal') }}
                                @elseif($site_settings->id  == 3)
                                    {{ trans('frontend.about.figures') }}
                                @elseif($site_settings->id  == 4)
                                    {{ trans('frontend.about.shirti') }}
                                @else
                                    {{ trans('frontend.about.ebtekar') }}
                                @endif
                            </h2>
                            <ul>
                                <li><a href="{{route('home')}}">{{ trans('frontend.about.home') }}</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)">
                                    @if($site_settings->id  == 2)
                                        {{ trans('frontend.about.ertgal') }}
                                    @elseif($site_settings->id  == 3)
                                        {{ trans('frontend.about.figures') }}
                                    @elseif($site_settings->id  == 4)
                                        {{ trans('frontend.about.shirti') }}
                                    @else
                                        {{ trans('frontend.about.ebtekar') }}
                                    @endif
                                </a></li>
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
                    <p class="mb-2">{{ $site_settings->description }}</p>
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
                        @foreach($site_settings->photos as $key => $media)
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
