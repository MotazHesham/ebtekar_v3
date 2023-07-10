@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2> {{ trans('frontend.wishlists.list') }} </h2>
                            <ul>
                                <li><a href="{{ route('frontend.dashboard') }}">  {{ trans('frontend.about.dashboard') }} </a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="{{ route('frontend.wishlist') }}">  {{ trans('frontend.wishlists.list') }} </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="wishlist-section section-big-py-space b-g-light">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table cart-table table-responsive-xs">
                        <thead>
                            <tr class="table-head">
                                <th scope="col">{{ trans('frontend.wishlists.photo') }} </th>
                                <th scope="col">{{ trans('frontend.wishlists.product_name') }} </th>
                                <th scope="col">{{ trans('frontend.wishlists.price') }} </th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wishlists as $wishlist)
                                @if($wishlist->product)
                                    @php
                                        $image = '';
                                        $product = $wishlist->product;
                                        if($product->photos != null){
                                            $image = isset($product->photos[0]) ? $product->photos[0]->getUrl('preview2') : '';
                                        }
                                        $prices = product_price_in_cart(1,null,$product);
                                    @endphp
                                    <tr id="wishlist-tr-{{$wishlist->id}}">
                                        <td>
                                            <a href="{{ route('frontend.product', $product->slug) }}"><img
                                                    src="{{ $image }}" alt="product" class="img-fluid  "></a>
                                        </td>
                                        <td><a href="{{ route('frontend.product', $product->slug) }}">{{$product->name ?? ''}}</a>
                                            <div class="mobile-cart-content">
                                                <div class="col-xs-3">
                                                    <h2 class="td-color"><?php echo $prices['h2'] ?></h2>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h2 class="td-color"><a href="javascript:void(0)" class="icon me-1"><i
                                                                class="ti-close"></i> </a><a href="javascript:void(0)"
                                                            class="cart"><i class="ti-shopping-cart"></i></a></h2>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h2><?php echo $prices['h2'] ?></h2>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="deleteWishlistItem('{{$wishlist->id}}')" class="icon me-3"><i class="ti-close"></i> </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="product-pagination">
                        <div class="theme-paggination-block">
                            <div class="container-fluid p-0">
                                <div class="row">
                                    <div class="col-xl-8 col-md-8 col-sm-12">
                                        <nav aria-label="Page navigation">
                                            {{$wishlists->links('vendor.pagination.custom.products')}}
                                        </nav>
                                    </div>
                                    <div class="col-xl-4 col-md-4 col-sm-12">
                                        <div class="product-search-count-bottom">
                                            <span style="padding:5px"> عرض </span>

                                            @if ($wishlists->firstItem())
                                                <b> {{ $wishlists->firstItem() }} </b>

                                                <span style="padding:5px"> إلي </span>

                                                <b> {{ $wishlists->lastItem() }} </b>

                                            @else
                                                {{ $wishlists->count() }}
                                            @endif
                                            <span style="padding:5px"> من </span>

                                            <b> {{ $wishlists->total() }} </b>

                                            <span style="padding:5px"> النتائج </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row wishlist-buttons">
                <div class="col-12"><a href="{{ route('frontend.search') }}" class="btn btn-normal"> {{ trans('frontend.wishlists.shoping') }}  </a> <a
                        href="{{ route('frontend.cart') }}" class="btn btn-normal">    {{ trans('frontend.wishlists.cart') }} </a></div>
            </div>
        </div>
    </section>
    <!--section end-->
@endsection
