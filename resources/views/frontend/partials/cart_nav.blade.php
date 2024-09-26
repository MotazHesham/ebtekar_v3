<ul class="cart_product">
    @php
        $total = 0;
        $count_cart = session('cart') ? session('cart')->count() : 0;
    @endphp
    @if (session('cart')) 
        @foreach (session('cart') as $key => $cartItem)
            @php
                $product = \App\Models\Product::find($cartItem['product_id']);
                $image = '';
                if ($product) {
                    if ($product->photos != null) {
                        $image = isset($product->photos[0]) ? $product->photos[0]->getUrl('thumb') : '';
                    }
                    $prices = product_price_in_cart($cartItem['quantity'], $cartItem['variation'], $product);
                    $total += $prices['price']['value'] * $cartItem['quantity'];
                }
            @endphp
            @if ($product)
                <li class="cart-{{ $cartItem['id'] }}">
                    <div class="media">
                        <a {{ route('frontend.product', $product->slug) }}>
                            <img alt="megastore1" class="me-3" src="{{ $image }}">
                        </a>
                        <div class="media-body">
                            <a {{ route('frontend.product', $product->slug) }}>
                                <h4>{{ $product->name }}</h4>
                                <small>{{ $cartItem['variation'] }}</small>
                            </a>
                            <h6>
                                <?php echo $prices['h2']; ?>
                            </h6>
                            <div class="addit-box">
                                <div class="qty-box">
                                    <div class="input-group">
                                        <button class="qty-minus"
                                            onclick="updateCartItem('{{ $cartItem['id'] }}',-1,'cart-qty-')"></button>
                                        <input class="qty-adj form-control" disabled type="number"
                                            id="cart-qty-{{ $cartItem['id'] }}" value="{{ $cartItem['quantity'] }}"
                                            min="1" />
                                        <button class="qty-plus"
                                            onclick="updateCartItem('{{ $cartItem['id'] }}',1,'cart-qty-')"></button>
                                    </div>
                                </div>
                                <div class="pro-add">
                                    <a href="javascript:void(0)" onclick="edit_cart('{{$cartItem['id']}}')">
                                        <i data-feather="edit" style="font-size:15px"></i>
                                    </a>
                                    <a href="{{ route('frontend.cart.delete', $cartItem['id']) }}">
                                        <i data-feather="trash-2" style="font-size:15px"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endif
        @endforeach
    @endisset
</ul>
<ul class="cart_total">
<li>
    <div class="total">
        {{ __('frontend.cart.total') }}
        <span>
            {{ $total ?? '' }} {{ $prices['price']['symbol'] ?? '' }}
        </span>
    </div>
</li>
<li>
    <div class="buttons">
        <a href="{{ route('frontend.cart') }}" class="btn btn-solid btn-sm">{{ __('frontend.cart.show_cart') }}</a>
        <a href="{{ route('frontend.payment_select') }}" class="btn btn-solid btn-sm " onclick="initiate_checkout_dataLayer('{{$total ?? 0}}','{{$count_cart}}')">{{ __('frontend.cart.payment_direct') }}</a>
    </div>
</li>
</ul>
