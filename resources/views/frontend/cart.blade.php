@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>سلة التسوق</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                                <li><i class="fa fa-angle-double-right"></i></li>
                                <li><a href="{{ route('frontend.cart') }}">سلة التسوق</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->


    <!--section start-->
    <section class="cart-section section-big-py-space b-g-light">
        <div class="custom-container">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table cart-table table-responsive-xs">
                        <thead>
                            <tr class="table-head">
                                <th scope="col">صورة المنتج</th>
                                <th scope="col">اسم المنتج</th>
                                <th scope="col">السعر</th>
                                <th scope="col">العدد</th>
                                <th scope="col"></th>
                                <th scope="col">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @if(session('cart'))
                                @foreach(session('cart') as $cartItem)
                                    @php
                                        $product = \App\Models\Product::find($cartItem['product_id']); 
                                        $image = '';
                                        if($product){
                                            if($product->photos != null){ 
                                                $image = isset($product->photos[0]) ? $product->photos[0]->getUrl('preview2') : '';
                                            } 
                                            $prices = product_price_in_cart($cartItem['quantity'],$cartItem['variation'],$product);
                                            $total += ($prices['price']['value'] * $cartItem['quantity'] );
                                        }
                                    @endphp 
                                    @if($product)
                                        <tr id="tr-cart-{{$cartItem['id']}}">
                                            <td>
                                                <a href="{{ route('frontend.product', $product->slug) }}"><img
                                                        src="{{ $image }}" alt="product" class="img-fluid  "></a>
                                            </td>
                                            <td>
                                                <a href="{{ route('frontend.product', $product->slug) }}">
                                                    {{$product->name ?? ''}}
                                                    <br>
                                                    <b style="font-size:12px">@if($cartItem['variation']) ({{ $cartItem['variation'] }}) @endif</b>
                                                </a>
                                                <div class="mobile-cart-content">
                                                    <div class="col-xs-3">
                                                        <div class="qty-box">
                                                            <b id="mob-cart-qty-{{$cartItem['id']}}">{{ $cartItem['quantity'] }}x</b>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <h2 class="td-color"> 
                                                            <?php echo $prices['h2']; ?>  
                                                        </h2>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <h2 class="td-color">
                                                            <a href="{{ route('frontend.cart.delete',$cartItem['id']) }}" class="icon">
                                                                <i class="ti-close"></i>
                                                            </a>
                                                        </h2>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h2> 
                                                    <?php echo $prices['h2']; ?>  
                                                </h2>
                                            </td>
                                            <td>
                                                <div class="qty-box">
                                                    <div class="input-group">
                                                        <button class="qty-minus" onclick="updateCartItem('{{$cartItem['id']}}',-1,'tr-cart-qty-')"></button>
                                                        <input type="number" name="quantity" class="form-control input-number"
                                                            value="{{ $cartItem['quantity'] }}" id="tr-cart-qty-{{$cartItem['id']}}" disabled>
                                                        <button class="qty-plus" onclick="updateCartItem('{{$cartItem['id']}}',1,'tr-cart-qty-')"></button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><a href="{{ route('frontend.cart.delete',$cartItem['id']) }}" class="icon"><i class="ti-close"></i></a></td>
                                            <td>
                                                <h2 class="td-color" id="td-total-{{$cartItem['id']}}">{{  ($prices['price']['value'] * $cartItem['quantity'])  }} {{ $prices['price']['symbol'] }}</h2>
                                            </td>
                                        </tr> 
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <table class="table cart-table table-responsive-md">
                        <tfoot>
                            <tr>
                                <td>إجمالي السعر :</td>
                                <td>
                                    <h2 id="td-total-cost">  {{  $total  }} {{ $prices['price']['symbol'] }} </h2>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row cart-buttons">
                <div class="col-12"><a href="{{ route('frontend.search') }}" class="btn btn-normal">استمر في التسوق</a> <a
                        href="{{ route('frontend.payment_select') }}" class="btn btn-normal ms-3">اتمام عملية الدفع</a></div>
            </div>
        </div>
    </section>
    <!--section end-->
@endsection
