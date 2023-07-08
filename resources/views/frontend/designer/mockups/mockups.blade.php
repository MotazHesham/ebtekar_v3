
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
                                <li><a href="{{ route('frontend.dashboard') }}">لوحة التحكم</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="{{ route('frontend.mockups.categories') }}">أبدا التصميم</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- section start -->
    <section class="section-big-py-space b-g-light">
        <div class="container">
            <div class="row">

                <!-- front_navbar start-->
                    @include('frontend.partials.dashboard_navbar')
                <!-- front_navbar End -->

                <div class="col-lg-9">
                    <div class="dashboard-right">
                        <div class="card">
                            <div class="card-header">Mockups</div>
                            <div class="card-body">
                                <div class="row"> 
                                    @foreach ($mockups as $key => $mockup)  
                                        <div class="col-md-4"> 
                                            <div class="text-center" style="background:white"> 
                                                <a href="{{route('frontend.designs.start',$mockup->id)}}">
                                                    <div style="background:{{json_decode($mockup->colors)[0] ?? ''}}">
                                                        @if($mockup->preview_1 != null)
                                                            @php
                                                                $prev_1 = json_decode($mockup->preview_1);
                                                                $image_1 = $prev_1 ? $prev_1->image  : ''; 
                                                            @endphp
                                                            <img src="{{asset($image_1)}}" class="img-fluid"  alt="" >
                                                        @endif
                                                    </div> 
                                                    <div class="mt-3" style="padding: 15px;">
                                                        {{$mockup->name}}
                                                        <br>
                                                        {{ $mockup->purchase_price }} 
                                                    </div>
                                                </a>
                                            </div> 
                                        </div>
                                    @endforeach
                                </div>
                                {{ $mockups->links() }}
                            </div>
                        </div> 
                    </div>
                </div> 
            </div>
        </div>
    </section> 
@endsection
