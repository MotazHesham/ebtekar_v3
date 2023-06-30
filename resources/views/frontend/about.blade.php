@extends('frontend.layout.app')

@section('content')
    <!-- about section start -->
    <section class="about-page section-big-py-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="banner-section"><img src="assets/images/blog/1.jpg" class="img-fluid   w-100" alt="">
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
                        <div>
                            <div class="testimonial-contain">
                                <div class="media">
                                    <div class="testimonial-img">
                                        <img src="{{ asset('frontend/assets/images/testimonial/1.jpg') }}" class="img-fluid rounded-circle  "
                                            alt="testimonial">
                                    </div>
                                    <div class="media-body">
                                        <h5>محمد احمد</h5>
                                        <p> كما تريد، النص لن يبدو مقسما ولا يحوي أخطاء لغوية، مولد النص العربى مفيد لمصممي
                                            المواقع على وجه الخصوص، حيث يحتاج العميل فى كثير من الأحيان أن يطلع على صورة
                                            حقيقية لتصميم الموقع.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="testimonial-contain">
                                <div class="media">
                                    <div class="testimonial-img">
                                        <img src="{{ asset('frontend/assets/images/testimonial/2.jpg') }}" class="img-fluid rounded-circle"
                                            alt="testimonial">
                                    </div>
                                    <div class="media-body">
                                        <h5>محمد احمد</h5>

                                        <p> كما تريد، النص لن يبدو مقسما ولا يحوي أخطاء لغوية، مولد النص العربى مفيد لمصممي
                                            المواقع على وجه الخصوص، حيث يحتاج العميل فى كثير من الأحيان أن يطلع على صورة
                                            حقيقية لتصميم الموقع.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="testimonial-contain">
                                <div class="media">
                                    <div class="testimonial-img">
                                        <img src="{{ asset('frontend/assets/images/testimonial/3.jpg') }}" class="img-fluid rounded-circle"
                                            alt="testimonial">
                                    </div>
                                    <div class="media-body">
                                        <h5>محمد احمد</h5>

                                        <p> كما تريد، النص لن يبدو مقسما ولا يحوي أخطاء لغوية، مولد النص العربى مفيد لمصممي
                                            المواقع على وجه الخصوص، حيث يحتاج العميل فى كثير من الأحيان أن يطلع على صورة
                                            حقيقية لتصميم الموقع.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--testimonial end-->
@endsection
