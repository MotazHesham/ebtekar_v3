<ul class="qty">
    @php
        $total = 0; 
        $symbol = 'EGP';
        $count_cart = session('cart') ? session('cart')->count() : 0;
    @endphp
    @if(session('cart'))
        @foreach(session('cart') as $cartItem)
            @php
                $product = \App\Models\Product::find($cartItem['product_id']); 
                if($product){
                    $prices = product_price_in_cart($cartItem['quantity'],$cartItem['variation'],$product);
                    $total += ($prices['price']['value'] * $cartItem['quantity'] );
                    $symbol = $prices['price']['symbol'];
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
                                {{ __('frontend.checkout.commission') }}:
                                <b> {{ $prices['commission'] }} {{ $prices['price']['symbol'] }}</b>
                            </small>
                        @endif
                    </span>
                    
                </li>
            @endif
        @endforeach
    @endif
</ul>
<ul class="qty">
    <li>{{ __('frontend.checkout.subtotal') }} <span class="count"> + {{  $total  }} {{ $symbol }}</span></li>
    <li>{{ __('frontend.checkout.shipping') }} <span class="count"> + {{  $shipping  }} {{ $symbol }}</span></li>
    @if($wrong_disocunt_code || $discount_code == null)
        <li> 
            <div class="form-group col-md-12 col-sm-12 col-xs-12"> 
                <div class="d-flex" style="justify-content: space-between;"> 
                    <input type="text" style="@if($wrong_disocunt_code) border-color:red; @endif width: 50%" name="discount_code" id="discount_code" value="{{old('discount_code')}}" placeholder="{{ __('frontend.checkout.discount_code') }}">
                    <button class="btn-dark btn" type="button" onclick="updateSummaryDiscountCode()">أضف الكود</button>
                </div>
                @if($wrong_disocunt_code)
                    <small style="color:red">كود غير صحيح ({{$discount_code}})</small>
                @endif
            </div> 
        </li>
    @else
        <input type="hidden" name="discount_code" id="discount_code" value="{{ $discount_code }}">
        <li>{{ __('frontend.checkout.discount') }} <span class="count"> - {{  $discount  }} {{ $symbol }}</span></li>
        <li>كود الخصم ({{$discount_code}}) <a href="#" style="color:red" onclick="updateSummaryDiscountCode(true)">حذف</a></li>
    @endif
</ul>
<ul class="total">
    <li>{{ __('frontend.checkout.total') }} <span class="count" style="text-align: left; !important"> = {{  $total - $discount + $shipping  }} {{ $symbol }}</span></li>
</ul>

@section('scripts') 
    @parent
    <script> 
    
        function updateSummaryDiscountCode(removeDiscountCode = false){ 
            var country_id = $('#country_id').val();
            var discount_code = removeDiscountCode ? null : $('#discount_code').val();
            $('#summary-spinner').css('display','block');
            $('#checkout-summary').css('display','none');
            $.post('{{ route('frontend.checkout.summary') }}', {_token:'{{ csrf_token() }}',country_id:country_id, discount_code:discount_code}, function(data){
                $('#checkout-summary').html(data);
                $('#summary-spinner').css('display','none');
                $('#checkout-summary').css('display','block');
            });
        } 
        @if(app()->isProduction() && $site_settings->tag_manager) 
            $('#checkout-order').on('click',function(){   
                checkoutOrder_dataLayer('{{$total}}','{{$count_cart}}');
            })
        @endif
    </script>
@endsection