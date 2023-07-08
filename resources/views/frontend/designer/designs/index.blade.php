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
                            <li><a href="javascript:void(0)"> تصميماتي </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb End -->


<!--section start-->
<section class="cart-section order-history section-big-py-space"> 
    <div class="custom-container"> 
        <div class="row"> 
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body pb-0">
                        <div class="text-value">{{ $total }}</div>
                        <div> الأجمالي    
                        </div>
                        <br />
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body pb-0">
                        <div class="text-value">{{ $pending }}</div>
                        <div>الأرباح قيدة التنفيذ   
                        </div>
                        <br />
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body pb-0">
                        <div class="text-value">{{ $available }}</div>
                        <div>الأرباح المتاحة للتوريد  
                        </div>
                        <br />
                    </div>
                </div>
            </div> 
        </div> 
        <div class="row mt-5">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header"> التصاميم
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th> 
                                    <th>اسم التصميم	</th> 
                                    <th>الربح للقطعة الواحدة</th>  
                                    <th>الحالة</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($designs as $key => $design)  
                                    <tr>  
                                        <td>{{ $design->id}}</td>
                                        <td>
                                            {{$design->design_name}}
                                        </td>
                                        <td>
                                            {{ $design->profit }}
                                        </td> 
                                        <td>
                                            @if($design->status == 'pending')
                                                <i class="fa fa-pause-circle" style="font-size: 30px; color: rgb(71, 121, 179);"></i> {{__('Pending')}}
                                            @elseif($design->status == 'accepted')
                                                @php
                                                    $product_slug = $design->product->slug ?? '';
                                                @endphp
                                                <i class="fa fa-check-circle" style="font-size: 30px; color: rgb(55, 189, 55);"></i>  {{__('Accepted')}}
                                                <a href="{{ route('frontend.product',$product_slug) }}" class="btn btn-info">عرض المنتج {{$design['product_slug']}}</a>
                                            @elseif($design->status == 'refused')
                                                <i class="fa fa-times-circle" style="font-size: 30px; color: rgb(180, 82, 82);"></i> {{__('Refused')}}
                                            @endif
                                        </td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
            <div class="product-pagination">
                <div class="theme-paggination-block">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-xl-8 col-md-8 col-sm-12">
                                <nav aria-label="Page navigation"> 
                                    {{ $designs->appends(request()->input())->links('vendor.pagination.custom.products') }}
                                </nav>
                            </div>
                            <div class="col-xl-4 col-md-4 col-sm-12">
                                <div class="product-search-count-bottom">
                                    <span style="padding:5px"> عرض </span>

                                    @if ($designs->firstItem())
                                        <b> {{ $designs->firstItem() }} </b>

                                        <span style="padding:5px"> إلي </span>

                                        <b> {{ $designs->lastItem() }} </b>

                                    @else
                                        {{ $designs->count() }}
                                    @endif
                                    <span style="padding:5px"> من </span>

                                    <b> {{ $designs->total() }} </b>

                                    <span style="padding:5px"> النتائج </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</section>
<!--section end--> 

@endsection 
