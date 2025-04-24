
@php
    $front_image = isset($product->photos[0]) ? $product->photos[0]->getUrl($preview) : '';
    $back_image = isset($product->photos[1]) ? $product->photos[1]->getUrl($preview) : $front_image;
@endphp 

<div>
    <div class="product-box product-box2">
        <div class="product-imgbox">
            <div class="product-front">
                <a href="{{ route('frontend.product', $product->slug) }}">
                    <img src="{{ $front_image }}" class="img-fluid" alt="{{ $product->name }}" onerror="this.onerror=null;this.src='{{ asset('placeholder.jpg') }}';">
                </a>
            </div>
            <div class="product-back">
                <a href="{{ route('frontend.product', $product->slug) }}">
                    <img src="{{ $back_image }}" class="img-fluid" alt="{{ $product->name }}" onerror="this.onerror=null;this.src='{{ asset('placeholder.jpg') }}';">
                </a>
            </div>
            <div class="product-icon icon-inline">
                @if($product->variant_product || $product->special)
                    <a href="{{ route('frontend.product', $product->slug) }}" class="tooltip-top add-cartnoty"  data-tippy-content="Add to cart">
                        <i data-feather="shopping-cart"></i>
                    </a>
                @else  
                    <form  action="{{route('frontend.cart.add')}}" method="POST" enctype="multipart/form-data" style="margin-left: 7px;">
                        @csrf
                        <input type="hidden" name="id" value="{{$product->id}}">
                        <input type="hidden" name="variant" >
                        <button type="submit" class="tooltip-top add-cartnoty"  data-tippy-content="Add to cart">
                            <i data-feather="shopping-cart"></i>
                        </button>
                    </form>
                @endif
                <a href="{{ route('frontend.wishlist.add',$product->slug) }}" class="add-to-wish tooltip-top"
                    data-tippy-content="Add to Wishlist">
                    <i data-feather="heart"></i>
                </a>
                <a href="javascript:void(0)" onclick="quick_view('{{$product->id}}')" data-bs-toggle="modal" data-bs-target="#quick-view"
                    class="tooltip-top" data-tippy-content="Quick View">
                    <i data-feather="eye"></i>
                </a>
            </div> 
            @if(auth()->check() && auth()->user()->user_type == 'seller')
                <div class="new-label1">
                    <div class="text-center"> <small> الربح  <br> {{ front_calc_commission_currency($product->unit_price,$product->purchase_price)['value'] }} </small> </div>
                </div> 
            @endif
        </div>
        <div class="product-detail product-detail2 ">
            <ul>
                @include('frontend.partials.rate',['rate' => $product->rating])
            </ul>
            <a href="{{ route('frontend.product', $product->slug) }}">
                <h3> {{ $product->name }} </h3>
            </a>
            <h5>
                {!! $product->calc_price_as_text() !!}  
            </h5>
        </div>
    </div>
</div>