@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2> طلباتي السابقة</h2>
                            <ul>
                                <li><a href="index.html">الرئيسية</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)"> طلباتي السابقة </a></li>
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
                <div class="col-sm-12">
                    <table class="table cart-table table-responsive-xs">
                        <thead>
                            <tr class="table-head">
                                <th scope="col">المنتج</th>
                                <th scope="col">الوصف</th>
                                <th scope="col">السعر</th>
                                <th scope="col">التفاصيل</th>
                                <th scope="col">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderDetails as $orderDetail)
                                @if($orderDetail->product)
                                    @php
                                        $image = '';
                                        $product = $orderDetail->product;
                                        if(json_decode($product->photos) != null && json_decode($product->photos)[0]){
                                            $image = json_decode($product->photos)[0] ?? '';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('frontend.product',$product->slug)}}"><img src="{{ image_asset($image) }}"
                                                    alt="product" class="img-fluid  "></a>
                                        </td>
                                        <td>
                                            <a href="{{ route('frontend.product',$product->slug)}}">رقم الطلب <span class="dark-data">{{$orderDetail->order->order_num ?? ''}}</span>
                                                <br>{{$product->name ?? ''}}</a>
                                        </td>
                                        <td>
                                            <b>{{ front_currency($orderDetail->total_cost) }}</b>
                                        </td>
                                        <td>
                                            <span> {{ $orderDetail->variation }} </span>
                                            <br>
                                            <span>العدد: {{ $orderDetail->quantity }}</span>
                                        </td>
                                        <td>
                                            <div class="responsive-data">
                                                <b>{{ front_currency($orderDetail->total_cost) }}</b>
                                                <br>
                                                <span> {{ $orderDetail->variation }} </span> | span>العدد: {{ $orderDetail->quantity }}</span>
                                            </div>
                                            <span class="dark-data">تم التسليم</span> (مارس ، 2020)
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row cart-buttons">
                <div class="col-12 pull-right"><a href="javascript:void(0)" class="btn btn-normal btn-sm">عرض الكل</a></div>
            </div>
        </div>
    </section>
    <!--section end-->
@endsection
