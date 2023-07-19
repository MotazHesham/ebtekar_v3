@extends('frontend.layout.app')

@section('styles') 
<link rel="stylesheet" href="{{ asset('dashboard_offline/css/bootstrap-datetimepicker.min.css') }}">
@endsection

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>{{ trans('frontend.checkout.payment') }}</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">{{ trans('frontend.about.home') }}</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="{{ route('frontend.checkout') }}">{{ trans('frontend.checkout.payment') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- section start -->
    <section class="section-big-py-space b-g-light">
        <div class="container">
            <div class="checkout-page contact-page">
                <div class="checkout-form">


                    @if ($errors->count() > 0)
                        <div class="alert alert-danger" style="background-color: #f8d7da;">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('frontend.checkout') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 col-xs-12">
                                <div class="checkout-title">
                                    <h3>{{ trans('frontend.checkout.receipt_details') }}</h3>
                                </div>
                                <div class="theme-form">
                                    @php
                                        $name = auth()->check() && auth()->user()->user_type != 'seller' ? explode(" ",auth()->user()->name) : '';
                                    @endphp
                                    <div class="row check-out ">
                                        @if(auth()->check() && auth()->user()->user_type == 'seller')
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label>{{ trans('frontend.checkout.date_of_receiving_order') }} </label>
                                                <input type="text" class="date" name="date_of_receiving_order" value="{{old('date_of_receiving_order')}}"  placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label> {{ trans('frontend.checkout.excepected_deliverd_date') }}</label>
                                                <input type="text" class="date" name="excepected_deliverd_date" value="{{old('excepected_deliverd_date')}}"  placeholder="">
                                            </div>  
                                        @endif
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label>   {{ trans('frontend.checkout.first_name') }} @if(auth()->check() && auth()->user()->user_type == 'seller') <small>(للعميل)</small> @endif</label>
                                            <input type="text" name="first_name" required value="{{ isset($name[0]) ? $name[0] : old('first_name')}}" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label>   {{ trans('frontend.checkout.last_name') }} @if(auth()->check() && auth()->user()->user_type == 'seller') <small>(للعميل)</small> @endif</label>
                                            <input type="text" name="last_name" required value="{{ isset($name[1]) ? $name[1] : old('last_name')}}" placeholder="">
                                        </div> 
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="field-label">{{ trans('frontend.checkout.phone_number') }}</label>
                                            <input type="text" name="phone_number" value="{{old('phone_number',auth()->user()->phone_number ?? '')}}" placeholder="" required>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="field-label">   {{ trans('frontend.checkout.phone_number_2') }}</label>
                                            <input type="text" name="phone_number_2" value="{{old('phone_number_2')}}" placeholder="">
                                        </div>
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12"> 
                                            @php
                                                $city_exist = false;
                                                foreach ($countries['cities'] as $city){
                                                    if(session('country_code') != 'EG' && $city->code == session('country_code')){
                                                        $city_exist = true;
                                                        $city_id = $city->id;
                                                        $city_name = $city->name;
                                                    }
                                                } 
                                            @endphp
                                            <label class="field-label">{{ trans('frontend.checkout.country_id') }}</label> 
                                            <select class="form-control" name="country_id" id="country_id" required>
                                                <option value="">{{ trans('cruds.receiptSocial.fields.shipping_country_id') }}</option> 
                                                @if($city_exist)
                                                    <option value={{ $city_id }} selected> {{ $city_name}}</option>
                                                @else 
                                                    @if(isset($countries['districts']))
                                                        <optgroup label="{{ __('Districts') }}">
                                                            @foreach ($countries['districts'] as $district)
                                                                <option value={{ $district->id }} @if($district->id == old('country_id')) selected @endif>
                                                                    {{ $district->name }} -  {{ dashboard_currency($district->cost) }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endif
                                                    @if(isset($countries['countries']))
                                                        <optgroup label="{{ __('Countries') }}">
                                                            @foreach ($countries['countries'] as $country)
                                                                <option value={{ $country->id }} @if($country->id == old('country_id')) selected @endif>
                                                                    {{ $country->name }} -  {{ dashboard_currency($country->cost) }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endif
                                                    @if(isset($countries['metro']))
                                                        <optgroup label="{{ __('Metro') }}">
                                                            @foreach ($countries['metro'] as $raw)
                                                                <option value={{ $raw->id }} @if($raw->id == old('country_id')) selected @endif>
                                                                    {{ $raw->name }} -  {{ dashboard_currency($raw->cost) }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endif
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <label class="field-label">{{ trans('frontend.checkout.shipping_address') }}</label>
                                            <input type="text" name="shipping_address" value="{{old('shipping_address',auth()->user()->address ?? '')}}" required>
                                        </div>
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <label class="field-label">   {{ trans('frontend.checkout.discount_code') }}</label>
                                            <input type="text" name="discount_code" value="{{old('discount_code')}}" placeholder="">
                                        </div>

                                        
                                        @guest
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="checkbox" name="create_account" id="account-option" onchange="create_account_with_order(this)"> 
                                                <label for="account-option">   {{ trans('frontend.checkout.create_account') }}</label>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-6 col-xs-12" id="email" style="display: none">
                                                <label>   {{ trans('frontend.checkout.email') }}</label>
                                                <input type="email" name="email" value="{{old('email')}}">
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-xs-12" id="password" style="display: none">
                                                <label class="field-label">   {{ trans('frontend.checkout.password') }}</label>
                                                <input type="password" name="password">
                                            </div>
                                        @endguest
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-xs-12">
                                <div class="checkout-details theme-form  section-big-mt-space">
                                    <div class="order-box">
                                        <div class="title-box">
                                            <div>{{ trans('frontend.checkout.products') }} <span>{{ trans('frontend.checkout.total') }}</span></div>
                                        </div>
                                        <ul class="qty">
                                            @php
                                                $total = 0;
                                            @endphp
                                            @if(session('cart'))
                                                @foreach(session('cart') as $cartItem)
                                                    @php
                                                        $product = \App\Models\Product::find($cartItem['product_id']); 
                                                        if($product){
                                                            $prices = product_price_in_cart($cartItem['quantity'],$cartItem['variation'],$product);
                                                            $total += ($prices['price']['value'] * $cartItem['quantity'] );
                                                        }
                                                    @endphp
                                                    @if($product)
                                                        <li>
                                                            {{ $product->name}}
                                                            (×{{ $cartItem['quantity'] }})
                                                            <span>
                                                                {{  ($prices['price']['value'] * $cartItem['quantity'])  }} {{ $prices['price']['symbol'] }}
                                                                @if(auth()->check() && auth()->user()->user_type == 'seller')
                                                                    <br>
                                                                    <small>
                                                                        {{ trans('frontend.checkout.commission') }}:
                                                                        <b> {{ $prices['commission'] }} {{ $prices['price']['symbol'] }}</b>
                                                                    </small>
                                                                @endif
                                                            </span>
                                                            
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                        <ul class="total">
                                            <li>{{ trans('frontend.checkout.total') }} <span class="count">{{  $total  }} {{ $prices['price']['symbol'] }}</span></li>
                                        </ul>
                                    </div>
                                    <div class="payment-box">
                                        <div class="upper-box">
                                            <div class="payment-options">

                                                @if(auth()->check() && auth()->user()->user_type == 'seller')
                                                    <div class="row check-out mb-4">
                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                            <label class="field-label">{{ trans('frontend.checkout.deposit_type') }}</label>
                                                            <select name="deposit_type">
                                                                @foreach(\App\Models\Order::DEPOSIT_TYPE_SELECT as $key => $value)
                                                                    <option value="{{$key}}" @if(old('deposit_type') == $key) selected @endif>{{$value}}</option> 
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                            <label>   {{ trans('frontend.checkout.deposit_amount') }} </label>
                                                            <input type="number" name="deposit_amount" value="{{old('deposit_amount')}}" placeholder="">
                                                        </div>
                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                            <label class="field-label">   {{ trans('frontend.checkout.free_shipping') }} </label>
                                                            <select name="free_shipping" id="free_shipping">
                                                                <option value="0" @if(old('free_shipping') == '0') selected @endif>No</option>
                                                                <option value="1" @if(old('free_shipping') == '1') selected @endif>Yes</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-sm-12 col-xs-12" style="display: none" id="free_shipping_reason">
                                                            <label>   {{ trans('frontend.checkout.free_shipping_reason') }}   </label>
                                                            <input type="text" name="free_shipping_reason" value="{{old('free_shipping_reason')}}" placeholder="">
                                                        </div>
                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12" id="shipping_cost_by_seller">
                                                            <label class="field-label">   {{ trans('frontend.checkout.shipping_cost_by_seller') }} </label>
                                                            <input type="number" value="{{old('shipping_cost_by_seller')}}" placeholder="" name="shipping_cost_by_seller">
                                                        </div>
                                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                            <label class="field-label">     {{ trans('frontend.checkout.total_cost_by_seller') }}</label>
                                                            <input type="number" value="{{old('total_cost_by_seller')}}" placeholder="" name="total_cost_by_seller">
                                                        </div> 
                                                        
                                                    </div>
                                                @endif
                                                <ul>

                                                    <li>
                                                        <div class="radio-option">
                                                            <input type="radio" name="payment_option" id="payment-1" value="cash_on_delivery" checked>
                                                            <label for="payment-1">   {{ trans('frontend.checkout.cash_on_delivery') }}  </label>
                                                        </div>
                                                    </li>
                                                    @if(session('country_code') == 'EG')
                                                        <li>
                                                            <div class="radio-option">
                                                                <input type="radio" name="payment_option" id="payment-2" value="paymob"  @if(old('payment_option') == 'paymob') checked @endif>
                                                                <label for="payment-2">   {{ trans('frontend.checkout.paymob') }}</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="radio-option">
                                                                <input type="radio" name="payment_option" id="payment-3" value="wallet"  @if(old('payment_option') == 'wallet') checked @endif>
                                                                <label for="payment-3">   {{ trans('frontend.checkout.wallet') }}</label>
                                                            </div>
                                                        </li>
                                                    @endif

                                                </ul>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn-normal btn">{{ trans('frontend.checkout.pay') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- section end -->
@endsection

@section('scripts')
    @parent 
    
    <script src="{{ asset('dashboard_offline/js/moment.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        $('#free_shipping').on('change',function(){ 
            var free_shipping = $('#free_shipping').val();
            if(free_shipping == '1'){
                $('#free_shipping_reason').css('display','block');
                $('#shipping_cost_by_seller').css('display','none');
            }else{
                $('#free_shipping_reason').css('display','none');
                $('#shipping_cost_by_seller').css('display','block');
            }
        })
        
        function create_account_with_order(el){ 
            if(el.checked){
                $('#password').css('display','block');
                $('#email').css('display','block');
            }else{
                $('#password').css('display','none');
                $('#email').css('display','none');
            }
        }
    </script>
@endsection