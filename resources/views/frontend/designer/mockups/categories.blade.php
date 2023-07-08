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
                            <div class="card-header">الفئات</div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($categories as $key => $row)
                                        <div class="col-md-4">
                                            <div class="text-center" style="background:white">
                                                <a href="{{ route('frontend.mockups', $row->id) }}">
                                                    <div style="height: 170px">
                                                        <img src="{{ $row->banner ? $row->banner->getUrl() : '' }}" class="rounded" style="width: 100%;height:100%"
                                                            alt="">
                                                    </div>
                                                    <div class="mt-3" style="padding: 15px;">
                                                        {{ $row->name }}
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                {{ $categories->links() }}
                            </div>
                        </div> 
                    </div>
                </div> 
            </div>
        </div>
    </section> 
@endsection
