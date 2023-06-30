@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>الدفع</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="{{ route('frontend.checkout') }}">الدفع</a></li>
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
                    <form method="POST" action="{{ route('frontend.checkout') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 col-xs-12">
                                <div class="checkout-title">
                                    <h3>تفاصيل فاتورتك</h3>
                                </div>
                                <div class="theme-form">
                                    @php
                                        $name = explode(" ",auth()->user()->name);
                                    @endphp
                                    <div class="row check-out ">
                                        @if(auth()->check() && auth()->user()->user_type != 'seller')
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label>تاريخ استلام الطلب </label>
                                                <input type="date" name="date_of_receiving_order"  placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label>ميعاد التوصيل المتوقع</label>
                                                <input type="date" name="excepected_deliverd_date"  placeholder="">
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                <label>أسم العميل</label>
                                                <input type="text" name="client_name" required value="{{ auth()->user()->name ?? ''}}" placeholder="">
                                            </div>
                                        @else
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label>الاسم الاول</label>
                                                <input type="text" name="first_name" required value="{{ $name[0] ?? ''}}" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label>الاسم الاخير</label>
                                                <input type="text" name="last_name" required value="{{ $name[1] ?? ''}}" placeholder="">
                                            </div>
                                        @endif
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="field-label">التليفون</label>
                                            <input type="text" name="phone_number" value="{{old('phone_number',auth()->user()->phone_number ?? '')}}" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="field-label">تليفون أخر</label>
                                            <input type="text" name="phone_number2" value="{{old('phone_number2')}}" placeholder="">
                                        </div>
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            @php
                                                $countries = \App\Models\Country::where('type','countries')->where('status',1)->get();
                                                $districts = \App\Models\Country::where('type','districts')->where('status',1)->get();
                                                $metro = \App\Models\Country::where('type','metro')->where('status',1)->get();
                                            @endphp
                                            <label class="field-label">المدينة</label>
                                            <select name="shipping_country_id">
                                                <optgroup label="المناطق">
                                                    @foreach($districts as $district)
                                                        <option value={{$district->id}}>{{$district->name}} - {{  $district->cost }}</option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="المحافظات">
                                                    @foreach($countries as $country)
                                                        <option value={{$country->id}}>{{$country->name}} - {{  $country->cost }}</option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="المترو">
                                                    @foreach($metro as $raw)
                                                        <option value={{$raw->id}}>{{$raw->name}} - {{  $raw->cost }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <label class="field-label">العنوان</label>
                                            <input type="text" name="shipping_address" value="{{old('shipping_address',auth()->user()->address ?? '')}}" >
                                        </div>
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <label class="field-label">كود الخصم</label>
                                            <input type="text" name="discount_code" value="{{old('discount_code')}}" placeholder="">
                                        </div>


                                        {{-- <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="checkbox" name="shipping-option" id="account-option"> &ensp;
                                            <label for="account-option">إنشاء حساب</label>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-xs-12">
                                <div class="checkout-details theme-form  section-big-mt-space">
                                    <div class="order-box">
                                        <div class="title-box">
                                            <div>المنتجات <span>الإجمالي</span></div>
                                        </div>
                                        <ul class="qty">
                                            @foreach($cart as $cartItem)
                                                @php
                                                    $product = $cartItem->product;
                                                @endphp
                                                @if($cartItem->product)
                                                    <li>{{ $product->name}} (×{{ $cartItem->quantity }}) <span>{{ front_currency($cartItem->total_cost) }}</span></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        <ul class="total">
                                            <li>الاجمالي <span class="count">{{ front_currency($cart->sum('total_cost')) }}</span></li>
                                        </ul>
                                    </div>
                                    <div class="payment-box">
                                        <div class="upper-box">
                                            <div class="payment-options">

                                                @if(auth()->check() && auth()->user()->user_type != 'seller')
                                                    <div class="row check-out mb-4">
                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                            <label class="field-label">العربون</label>
                                                            <select name="deposit">
                                                                <option value="Vodafone cash">Vodafone cash</option>
                                                                <option value="Etisalat cash">Etisalat cash</option>
                                                                <option value="Bank Account">Bank Account</option>
                                                                <option value="Cash">Cash</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                            <label>المبلغ المحول </label>
                                                            <input type="number" name="deposit_amount" value="" placeholder="">
                                                        </div>
                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                            <label class="field-label">الشحن مجانا </label>
                                                            <select name="free_shipping">
                                                                <option value="0">No</option>
                                                                <option value="1">Yes</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                            <label class="field-label">تكلفة الشحن </label>
                                                            <input type="number" value="" placeholder="" name="shipping_cost_by_seller">
                                                        </div>
                                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                            <label class="field-label">حساب اجمالي الأوردر</label>
                                                            <input type="number" value="" placeholder="" name="free_shipping_reason">
                                                        </div>
                                                    </div>
                                                @endif
                                                <ul>

                                                    <li>
                                                        <div class="radio-option">
                                                            <input type="radio" name="payment_option" id="payment-1" value="cash_on_delivery" checked>
                                                            <label for="payment-1">دفع عند الاستلام</label>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="radio-option">
                                                            <input type="radio" name="payment_option" id="payment-2" value="paymob">
                                                            <label for="payment-2">الدفع أونلاين</label>
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn-normal btn">اتمام
                                                عمليه الشراء</button>
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
