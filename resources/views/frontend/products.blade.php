@extends('frontend.layout.app')

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2> {{ __('frontend.products.main_categories') }}</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">{{ __('frontend.about.home') }} </a></li>
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
                                        <form action="" id="search-form">
                                            <div class="product-top-filter">
                                                <div class="container-fluid p-0">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="filter-main-btn ">
                                                                <span class="filter-btn ">
                                                                    <i class="fa fa-filter" aria-hidden="true"></i> {{ __('frontend.products.filter') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 position-relative">
                                                            <div class="product-filter-content horizontal-filter-mian ">
                                                                <div class="horizontal-filter-toggle">
                                                                    <h4><i data-feather="filter"></i>{{ __('frontend.products.filter') }} </h4>
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
                                                                    <select name="pagination" onchange="filter()">
                                                                        <option value="12" @if($pagination == '12') selected @endif> {{ __('frontend.products.paginate_12') }} </option>
                                                                        <option value="24" @if($pagination == '24') selected @endif> {{ __('frontend.products.paginate_24') }} </option>
                                                                        <option value="50" @if($pagination == '50') selected @endif> {{ __('frontend.products.paginate_50') }} </option>
                                                                        <option value="100" @if($pagination == '100') selected @endif>{{ __('frontend.products.paginate_100') }}  </option>
                                                                    </select>
                                                                </div>

                                                                <div class="horizontal-filter collection-filter">
                                                                    <div class="horizontal-filter-contain">
                                                                        <div class="collection-mobile-back">
                                                                            <span class="filter-back">
                                                                                <i class="fa fa-angle-left" aria-hidden="true"></i>
                                                                                {{ __('frontend.products.back')}}
                                                                            </span>
                                                                        </div>
                                                                        <div class="filter-group">
                                                                            <div class="collection-collapse-block">
                                                                                <h6 class="collapse-block-title">{{ __('frontend.products.colors') }} </h6>  
                                                                                <div class="collection-collapse-block-content">
                                                                                    <div class="color-selector">
                                                                                        <ul> 
                                                                                            @foreach ($all_colors as $key => $color)
                                                                                                @php
                                                                                                    $flag = false;
                                                                                                    if (isset($selected_colors)) {
                                                                                                        foreach ($selected_colors as $key => $selected_color) {
                                                                                                            if ($selected_color == $color) { 
                                                                                                                $flag = true;
                                                                                                                break; 
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                @endphp 
                                                                                                <li>
                                                                                                    <input type="checkbox" id="color-{{ $key }}" name="color[]"
                                                                                                        value="{{ $color }}" @if ($flag) checked @endif
                                                                                                        onchange="filter()">
                                                                                                    <label style="background: {{ $color }};"
                                                                                                        for="color-{{ $key }}" ></label>
                                                                                                        <span>{{ \App\Models\Color::where('code', $color)->first()->name ?? '' }}</span>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @foreach($attributes as $key => $attribute) 
                                                                            <div class="filter-group">
                                                                                <div class="collection-collapse-block">
                                                                                    <h6 class="collapse-block-title">{{ \App\Models\Attribute::find($attribute['id'])->name }}</h6>  
                                                                                    <div class="collection-collapse-block-content">
                                                                                        <div class="size-selector">                              
                                                                                            <div class="collection-brand-filter">
                                                                                                @if (array_key_exists('values', $attribute))
                                                                                                    @foreach ($attribute['values'] as $key => $value)
                                                                                                        @php
                                                                                                            $flag = false;
                                                                                                            if (isset($selected_attributes)) {
                                                                                                                foreach ($selected_attributes as $key => $selected_attribute) {
                                                                                                                    if ($selected_attribute['id'] == $attribute['id']) {
                                                                                                                        if (in_array($value, $selected_attribute['values'])) {
                                                                                                                            $flag = true;
                                                                                                                            break;
                                                                                                                        }
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        @endphp 
                                                                                                        <div class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                                            <input type="checkbox" class="custom-control-input form-check-input" 
                                                                                                                    name="attribute_{{ $attribute['id'] }}[]" 
                                                                                                                    id="attribute_{{ $attribute['id'] }}_value_{{ $value }}" 
                                                                                                                    value="{{ $value }}" @if ($flag) checked @endif
                                                                                                                    onchange="filter()">
                                                                                                            <label class="custom-control-label form-check-label" style="text-transform: none;" for="attribute_{{ $attribute['id'] }}_value_{{ $value }}">{{$value}}</label>
                                                                                                        </div>     
                                                                                                    @endforeach
                                                                                                @endif                             
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                        

                                                                        <div class="filter-group">
                                                                            <div class="collection-collapse-block">
                                                                                <h6 class="collapse-block-title">صنف حسب</h6>
                                                                                <div class="collection-collapse-block-content">
                                                                                    <div class="size-selector">
                                                                                        <div class="collection-brand-filter">
                                                                                            <div class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                                <input type="radio" class="custom-control-input form-check-input" name="sort_by" onchange="filter()" id="newest" @isset($sort_by) @if($sort_by == 'newest') checked @endif @endisset value="newest">
                                                                                                <label class="custom-control-label form-check-label" for="newest">{{ __('frontend.products.newest') }}</label>
                                                                                            </div> 
                                                                                            <div class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                                <input type="radio" class="custom-control-input form-check-input" name="sort_by" onchange="filter()" id="oldest" @isset($sort_by) @if($sort_by == 'oldest') checked @endif @endisset value="oldest">
                                                                                                <label class="custom-control-label form-check-label" for="oldest">{{ __('frontend.products.oldest') }}</label>
                                                                                            </div> 
                                                                                            <div class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                                <input type="radio" class="custom-control-input form-check-input" name="sort_by" onchange="filter()" id="price_low" @isset($sort_by) @if($sort_by == 'price_low') checked @endif @endisset value="price_low">
                                                                                                <label class="custom-control-label form-check-label" for="price_low">{{ __('frontend.products.price_low') }}   </label>
                                                                                            </div> 
                                                                                            <div class="custom-control custom-checkbox  form-check collection-filter-checkbox">
                                                                                                <input type="radio" class="custom-control-input form-check-input" name="sort_by" onchange="filter()" id="price_high" @isset($sort_by) @if($sort_by == 'price_high') checked @endif @endisset value="price_high">
                                                                                                <label class="custom-control-label form-check-label" for="price_high">{{ __('frontend.products.price_high') }}   </label>
                                                                                            </div> 
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <a href="javascript:void(0)" class="btn btn-solid btn-sm close-filter"> {{ __('frontend.products.close') }}</a> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="product-wrapper-grid product">
                                            <div class="row">
                                                @foreach($products as $product) 
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-6 col-grid-box">
                                                        @include('frontend.partials.single-product',['product' => $product, 'preview' => 'preview3'])
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
                                                                {{ $products->appends(request()->input())->links('vendor.pagination.custom.products') }}
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

@section('scripts')
    @parent 
    <script> 
        function filter() {
            $('#search-form').submit();
        }
    </script>
    <script>
        
        @if(isset($eventData))
            document.addEventListener('DOMContentLoaded', function () { 
                metaPixelEvent(@json($eventData)); 
            });
        @endif 
    </script>
@endsection