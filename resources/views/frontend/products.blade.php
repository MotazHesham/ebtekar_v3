@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>التصنيفات الرئيسية</h2>
                            <ul>
                                <li><a href="index.html">الصفحة الرئيسية</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)">{{ $title }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- section start -->
    <section class="section-big-pt-space ratio_asos b-g-light">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <div class="collection-content col">
                        <div class="page-main-content">
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="collection-product-wrapper">
                                        
                                        <div class="product-top-filter">
                                            <div class="container-fluid p-0">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="filter-main-btn ">
                                                            <span class="filter-btn ">
                                                                <i class="fa fa-filter" aria-hidden="true"></i> الفلتر
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 position-relative">
                                                        <div class="product-filter-content horizontal-filter-mian ">
                                                            <div class="horizontal-filter-toggle">
                                                                <h4><i data-feather="filter"></i>الفلتر </h4>
                                                            </div>
                                                            <div class="collection-view">
                                                                <ul>
                                                                    <li><i class="fa fa-th grid-layout-view"></i></li>
                                                                    <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                                </ul>
                                                            </div>
                                                            <div class="collection-grid-view">
                                                                <ul>
                                                                    <li><img src="assets/images/category/icon/2.png"
                                                                            alt="" class="product-2-layout-view">
                                                                    </li>
                                                                    <li><img src="assets/images/category/icon/3.png"
                                                                            alt="" class="product-3-layout-view">
                                                                    </li>
                                                                    <li><img src="assets/images/category/icon/4.png"
                                                                            alt="" class="product-4-layout-view">
                                                                    </li>
                                                                    <li><img src="assets/images/category/icon/6.png"
                                                                            alt="" class="product-6-layout-view">
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="product-page-per-view">
                                                                <select>
                                                                    <option value="High to low">عرض 24 نتيجة</option>
                                                                    <option value="Low to High">عرض 50 نتيجة</option>
                                                                    <option value="Low to High">عرض 100 نتيجة</option>
                                                                </select>
                                                            </div>

                                                            <div class="horizontal-filter collection-filter">
                                                                <div class="horizontal-filter-contain">
                                                                    <div class="collection-mobile-back"><span
                                                                            class="filter-back"><i class="fa fa-angle-left"
                                                                                aria-hidden="true"></i> عودة</span></div>
                                                                    <div class="filter-group">
                                                                        <div class="collection-collapse-block">
                                                                            <h6 class="collapse-block-title">الحجم</h6>
                                                                            <div class="collection-collapse-block-content">
                                                                                <div class="color-selector">
                                                                                    <ul>
                                                                                        <li>
                                                                                            <div class="color-1 active">
                                                                                            </div> white (14)
                                                                                        </li>
                                                                                        <li>
                                                                                            <div class="color-2"></div>
                                                                                            brown(24)
                                                                                        </li>
                                                                                        <li>
                                                                                            <div class="color-3"></div>
                                                                                            red(18)
                                                                                        </li>
                                                                                        <li>
                                                                                            <div class="color-4"></div>
                                                                                            purple(10)
                                                                                        </li>
                                                                                        <li>
                                                                                            <div class="color-5"></div>
                                                                                            teal(9)
                                                                                        </li>
                                                                                        <li>
                                                                                            <div class="color-6"></div>
                                                                                            pink(11)
                                                                                        </li>
                                                                                        <li>
                                                                                            <div class="color-7"></div>
                                                                                            coral(15)
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="filter-group">
                                                                        <div class="collection-collapse-block">
                                                                            <h6 class="collapse-block-title">الابعاد</h6>
                                                                            <div class="collection-collapse-block-content">
                                                                                <div class="size-selector">
                                                                                    <div class="collection-brand-filter">
                                                                                        <div
                                                                                            class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input form-check-input"
                                                                                                id="xssmall">
                                                                                            <label
                                                                                                class="custom-control-label form-check-label"
                                                                                                for="xssmall">xs</label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input form-check-input"
                                                                                                id="small">
                                                                                            <label
                                                                                                class="custom-control-label form-check-label"
                                                                                                for="small">s</label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input form-check-input"
                                                                                                id="mediam">
                                                                                            <label
                                                                                                class="custom-control-label form-check-label"
                                                                                                for="mediam">m</label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input form-check-input"
                                                                                                id="large">
                                                                                            <label
                                                                                                class="custom-control-label form-check-label"
                                                                                                for="large">l</label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input form-check-input"
                                                                                                id="extralarge">
                                                                                            <label
                                                                                                class="custom-control-label form-check-label"
                                                                                                for="extralarge">xl</label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input form-check-input"
                                                                                                id="2extralarge">
                                                                                            <label
                                                                                                class="custom-control-label form-check-label"
                                                                                                for="2extralarge">2xl</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="filter-group">
                                                                        <div class="collection-collapse-block">
                                                                            <h6 class="collapse-block-title">السعر</h6>
                                                                            <div class="collection-collapse-block-content">
                                                                                <div class="size-selector">
                                                                                    <div class="collection-brand-filter">
                                                                                        <div
                                                                                            class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input form-check-input"
                                                                                                id="hundred">
                                                                                            <label
                                                                                                class="custom-control-label form-check-label"
                                                                                                for="hundred">le 100 -
                                                                                                le1000</label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input form-check-input"
                                                                                                id="twohundred">
                                                                                            <label
                                                                                                class="custom-control-label form-check-label"
                                                                                                for="twohundred">1000 le -
                                                                                                2000le</label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input form-check-input"
                                                                                                id="threehundred">
                                                                                            <label
                                                                                                class="custom-control-label form-check-label"
                                                                                                for="threehundred">le 3000
                                                                                                - le 4000</label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input form-check-input"
                                                                                                id="fourhundred">
                                                                                            <label
                                                                                                class="custom-control-label form-check-label"
                                                                                                for="fourhundred">le300 -
                                                                                                le 400</label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input form-check-input"
                                                                                                id="fourhundredabove">
                                                                                            <label
                                                                                                class="custom-control-label form-check-label"
                                                                                                for="fourhundredabove">le
                                                                                                400 واكثر</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <a href="javascript:void(0)"
                                                                    class="btn btn-solid btn-sm close-filter"> اغلاق</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="product-wrapper-grid product">
                                            <div class="row">
                                                @foreach($products as $product)
                                                    @php
                                                        $front_image = $product->photos[0] ? $product->photos[0]->getUrl('preview2') : '';
                                                        $back_image = $product->photos[1] ? $product->photos[1]->getUrl('preview2') : $front_image;
                                                    @endphp 
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-6 col-grid-box">
                                                        <div>
                                                            <div class="product-box product-box2">
                                                                <div class="product-imgbox">
                                                                    <div class="product-front">
                                                                        <a href="{{ route('frontend.product',$product->slug) }}">
                                                                            <img src="{{ $front_image }}"
                                                                                class="img-fluid" alt="product">
                                                                        </a>
                                                                    </div>
                                                                    <div class="product-back">
                                                                        <a href="{{ route('frontend.product',$product->slug) }}">
                                                                            <img src="{{ $back_image }}"
                                                                                class="img-fluid" alt="product">
                                                                        </a>
                                                                    </div>
                                                                    <div class="product-icon icon-inline">
                                                                        {{-- <button class="tooltip-top add-cartnoty" data-tippy-content="Add to cart">
                                                                            <i data-feather="shopping-cart"></i>
                                                                        </button> --}}
                                                                        <a href="{{ route('frontend.wishlist.add',$product->slug) }}" class="add-to-wish tooltip-top"
                                                                            data-tippy-content="Add to Wishlist">
                                                                            <i data-feather="heart"></i>
                                                                        </a>
                                                                        <a href="javascript:void(0)" onclick="quick_view('{{$product->id}}')" data-bs-toggle="modal" data-bs-target="#quick-view"
                                                                            class="tooltip-top" data-tippy-content="Quick View">
                                                                            <i data-feather="eye"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="product-detail product-detail2 ">
                                                                    <ul>
                                                                        <li><i class="fa fa-star"></i></li>
                                                                        <li><i class="fa fa-star"></i></li>
                                                                        <li><i class="fa fa-star"></i></li>
                                                                        <li><i class="fa fa-star"></i></li>
                                                                        <li><i class="fa fa-star-o"></i></li>
                                                                    </ul>
                                                                    <a href="{{ route('frontend.product',$product->slug) }}">
                                                                        <h3> {{ $product->name }} </h3>
                                                                    </a>
                                                                    <h5>
                                                                        @if($product->discount > 0)
                                                                            {{front_currency($product->calc_discount($product->unit_price))}}
                                                                            <span>
                                                                                {{ front_currency($product->unit_price) }}
                                                                            </span>
                                                                        @else
                                                                            {{ front_currency($product->unit_price) }}
                                                                        @endif
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="product-pagination">
                                            <div class="theme-paggination-block">
                                                <div class="container-fluid p-0">
                                                    <div class="row">
                                                        <div class="col-xl-8 col-md-8 col-sm-12">
                                                            <nav aria-label="Page navigation">
                                                                {{$products->links('vendor.pagination.custom.products')}}
                                                            </nav>
                                                        </div>
                                                        <div class="col-xl-4 col-md-4 col-sm-12">
                                                            <div class="product-search-count-bottom">
                                                                <span style="padding:5px"> عرض </span>

                                                                @if ($products->firstItem())
                                                                    <b> {{ $products->firstItem() }} </b>

                                                                    <span style="padding:5px"> إلي </span>

                                                                    <b> {{ $products->lastItem() }} </b>

                                                                @else
                                                                    {{ $products->count() }}
                                                                @endif
                                                                <span style="padding:5px"> من </span>

                                                                <b> {{ $products->total() }} </b>

                                                                <span style="padding:5px"> النتائج </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- section End -->
@endsection
