<header id="stickyheader">
    <div class="mobile-fix-option"></div>

    <!-- top-header start-->
    <div class="top-header">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-md-7 col-sm-6">
                    <div class="top-header-left"> 
                    </div>
                </div>
                <div class="col-xl-7 col-md-5 col-sm-6">
                    <div class="top-header-right">

                        <div class="language-block" >
                            <div class="language-dropdown">
                                <span class="language-dropdown-click" style="cursor: pointer">
                                    {{ strtoupper(app()->getLocale()) }} <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </span>
                                <ul class="language-dropdown-open"> 
                                    <li>
                                        @foreach (config('panel.available_languages') as $langLocale => $langName)
                                            <a href="{{ url()->current() }}?change_language={{ $langLocale }}"> 
                                                {{ $langName }}
                                            </a>
                                        @endforeach
                                    </li>  
                                </ul>
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- top-header end-->

    <!-- mid-header start-->
    <div class="layout-header2">
        <div class="container">
            <div class="col-md-12">
                <div class="main-menu-block">
                    <div class="header-left">

                        <div class="brand-logo logo-sm-center">
                            <a href="{{ route('home') }}">
                                <img src="{{ $site_settings->logo ? $site_settings->logo->getUrl('preview') : '' }}" @if($site_settings->id == 3) width="75%" @else width="50%" @endif  class="img-fluid" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="input-block">
                        <div class="input-box">
                            <form class="big-deal-form" action="{{route('frontend.search')}}">
                                <div class="input-group ">
                                    <span class="search"><i class="fa fa-search"></i></span>
                                    <input type="text" class="form-control" name="search" value="@if(isset($search)) {{$search}} @endif" placeholder="{{ __('frontend.header.search_product') }}">
                                    <select name="category">
                                        <option value=""> {{ __('frontend.header.all_products') }}</option>
                                        @foreach ($header_home_categories as $homecategory)
                                            <option value="{{$homecategory->category->slug ?? ''}}" @if(isset($category) && $category == $homecategory->category->id) selected @endif>
                                                {{ $homecategory->category->name ?? '' }}
                                            </option>
                                        @endforeach 
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="header-right">
                        <div class="icon-block">
                            <ul>
                                <li class="mobile-search">
                                    <a href="javascript:void(0)">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 612.01 612.01" style="enable-background:new 0 0 612.01 612.01;"
                                            xml:space="preserve">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M606.209,578.714L448.198,423.228C489.576,378.272,515,318.817,515,253.393C514.98,113.439,399.704,0,257.493,0
                                                                C115.282,0,0.006,113.439,0.006,253.393s115.276,253.393,257.487,253.393c61.445,0,117.801-21.253,162.068-56.586
                                                                l158.624,156.099c7.729,7.614,20.277,7.614,28.006,0C613.938,598.686,613.938,586.328,606.209,578.714z M257.493,467.8
                                                                c-120.326,0-217.869-95.993-217.869-214.407S137.167,38.986,257.493,38.986c120.327,0,217.869,95.993,217.869,214.407
                                                                S377.82,467.8,257.493,467.8z" />
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                </li>
                                <li class="mobile-user " >
                                    <a @auth href="{{ route('frontend.dashboard') }}" @else href="{{ route('login') }}" @endauth> 
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                            xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path
                                                        d="M256,0c-74.439,0-135,60.561-135,135s60.561,135,135,135s135-60.561,135-135S330.439,0,256,0z M256,240
                                                            c-57.897,0-105-47.103-105-105c0-57.897,47.103-105,105-105c57.897,0,105,47.103,105,105C361,192.897,313.897,240,256,240z" />
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M297.833,301h-83.667C144.964,301,76.669,332.951,31,401.458V512h450V401.458C435.397,333.05,367.121,301,297.833,301z
                                                            M451.001,482H451H61v-71.363C96.031,360.683,152.952,331,214.167,331h83.667c61.215,0,118.135,29.683,153.167,79.637V482z" />
                                                </g>
                                            </g>
                                        </svg> 
                                    </a>
                                </li>
                                <li class="mobile-setting">
                                    <a href="{{route('home')}}"><i class="fa fa-home" aria-hidden="true"></i> </a> 
                                </li>
                                <li class="mobile-wishlist item-count" onclick="openWishlist()">
                                    <a @guest href="{{ route('login') }}" @endguest>
                                        <svg viewBox="0 -28 512.001 512" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="m256 455.515625c-7.289062 0-14.316406-2.640625-19.792969-7.4375-20.683593-18.085937-40.625-35.082031-58.21875-50.074219l-.089843-.078125c-51.582032-43.957031-96.125-81.917969-127.117188-119.3125-34.644531-41.804687-50.78125-81.441406-50.78125-124.742187 0-42.070313 14.425781-80.882813 40.617188-109.292969 26.503906-28.746094 62.871093-44.578125 102.414062-44.578125 29.554688 0 56.621094 9.34375 80.445312 27.769531 12.023438 9.300781 22.921876 20.683594 32.523438 33.960938 9.605469-13.277344 20.5-24.660157 32.527344-33.960938 23.824218-18.425781 50.890625-27.769531 80.445312-27.769531 39.539063 0 75.910156 15.832031 102.414063 44.578125 26.191406 28.410156 40.613281 67.222656 40.613281 109.292969 0 43.300781-16.132812 82.9375-50.777344 124.738281-30.992187 37.398437-75.53125 75.355469-127.105468 119.308594-17.625 15.015625-37.597657 32.039062-58.328126 50.167969-5.472656 4.789062-12.503906 7.429687-19.789062 7.429687zm-112.96875-425.523437c-31.066406 0-59.605469 12.398437-80.367188 34.914062-21.070312 22.855469-32.675781 54.449219-32.675781 88.964844 0 36.417968 13.535157 68.988281 43.882813 105.605468 29.332031 35.394532 72.960937 72.574219 123.476562 115.625l.09375.078126c17.660156 15.050781 37.679688 32.113281 58.515625 50.332031 20.960938-18.253907 41.011719-35.34375 58.707031-50.417969 50.511719-43.050781 94.136719-80.222656 123.46875-115.617188 30.34375-36.617187 43.878907-69.1875 43.878907-105.605468 0-34.515625-11.605469-66.109375-32.675781-88.964844-20.757813-22.515625-49.300782-34.914062-80.363282-34.914062-22.757812 0-43.652344 7.234374-62.101562 21.5-16.441406 12.71875-27.894532 28.796874-34.609375 40.046874-3.453125 5.785157-9.53125 9.238282-16.261719 9.238282s-12.808594-3.453125-16.261719-9.238282c-6.710937-11.25-18.164062-27.328124-34.609375-40.046874-18.449218-14.265626-39.34375-21.5-62.097656-21.5zm0 0" />
                                        </svg>

                                        @auth
                                            <div class="item-count-contain">
                                                {{ auth()->user()->wishlists->count() }}
                                            </div>
                                        @endauth
                                    </a>
                                </li>
                                <li class="mobile-cart item-count" @if(!request()->is("cart*")) onclick="openCart()" @endif >
                                    <a @if(request()->is("cart*"))  href="{{ route('frontend.cart') }}" @endif>
                                        <div class="cart-block">
                                            <div class="cart-icon">
                                                <svg enable-background="new 0 0 512 512" viewBox="0 0 512 512"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <g>
                                                        <path
                                                            d="m497 401.667c-415.684 0-397.149.077-397.175-.139-4.556-36.483-4.373-34.149-4.076-34.193 199.47-1.037-277.492.065 368.071.065 26.896 0 47.18-20.377 47.18-47.4v-203.25c0-19.7-16.025-35.755-35.725-35.79l-124.179-.214v-31.746c0-17.645-14.355-32-32-32h-29.972c-17.64 0-31.99 14.351-31.99 31.99v31.594l-133.21-.232-9.985-54.992c-2.667-14.694-15.443-25.36-30.378-25.36h-68.561c-8.284 0-15 6.716-15 15s6.716 15 15 15c72.595 0 69.219-.399 69.422.719 16.275 89.632 5.917 26.988 49.58 306.416l-38.389.2c-18.027.069-32.06 15.893-29.81 33.899l4.252 34.016c1.883 15.06 14.748 26.417 29.925 26.417h26.62c-18.8 36.504 7.827 80.333 49.067 80.333 41.221 0 67.876-43.813 49.067-80.333h142.866c-18.801 36.504 7.827 80.333 49.067 80.333 41.22 0 67.875-43.811 49.066-80.333h31.267c8.284 0 15-6.716 15-15s-6.716-15-15-15zm-209.865-352.677c0-1.097.893-1.99 1.99-1.99h29.972c1.103 0 2 .897 2 2v111c0 8.284 6.716 15 15 15h22.276l-46.75 46.779c-4.149 4.151-10.866 4.151-15.015 0l-46.751-46.779h22.277c8.284 0 15-6.716 15-15v-111.01zm-30 61.594v34.416h-25.039c-20.126 0-30.252 24.394-16.014 38.644l59.308 59.342c15.874 15.883 41.576 15.885 57.452 0l59.307-59.342c14.229-14.237 4.13-38.644-16.013-38.644h-25.039v-34.254l124.127.214c3.186.005 5.776 2.603 5.776 5.79v203.25c0 10.407-6.904 17.4-17.18 17.4h-299.412l-35.477-227.039zm-56.302 346.249c0 13.877-11.29 25.167-25.167 25.167s-25.166-11.29-25.166-25.167 11.29-25.167 25.167-25.167 25.166 11.291 25.166 25.167zm241 0c0 13.877-11.289 25.167-25.166 25.167s-25.167-11.29-25.167-25.167 11.29-25.167 25.167-25.167 25.166 11.291 25.166 25.167z" />
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="cart-item">
                                                <h5>{{ __('frontend.header.cart') }} </h5>
                                                <h5>{{ __('frontend.header.shoping') }}</h5> 
                                            </div>
                                        </div>
                                    </a> 
                                    <div class="item-count-contain">
                                        {{ session('cart') ? session('cart')->count() : 0 }}
                                    </div> 
                                </li>
                            </ul>
                        </div>
                        <div class="menu-nav">
                            <span class="toggle-nav">
                                <i class="fa fa-bars "></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ route('frontend.search') }}">
            <div class="searchbar-input">
                <div class="input-group">
                    <span class="input-group-text">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" width="28.931px" height="28.932px" viewBox="0 0 28.931 28.932"
                            style="enable-background:new 0 0 28.931 28.932;" xml:space="preserve">
                            <g>
                                <path
                                    d="M28.344,25.518l-6.114-6.115c1.486-2.067,2.303-4.537,2.303-7.137c0-3.275-1.275-6.355-3.594-8.672C18.625,1.278,15.543,0,12.266,0C8.99,0,5.909,1.275,3.593,3.594C1.277,5.909,0.001,8.99,0.001,12.266c0,3.276,1.275,6.356,3.592,8.674c2.316,2.316,5.396,3.594,8.673,3.594c2.599,0,5.067-0.813,7.136-2.303l6.114,6.115c0.392,0.391,0.902,0.586,1.414,0.586c0.513,0,1.024-0.195,1.414-0.586C29.125,27.564,29.125,26.299,28.344,25.518z M6.422,18.111c-1.562-1.562-2.421-3.639-2.421-5.846S4.86,7.983,6.422,6.421c1.561-1.562,3.636-2.422,5.844-2.422s4.284,0.86,5.845,2.422c1.562,1.562,2.422,3.638,2.422,5.845s-0.859,4.283-2.422,5.846c-1.562,1.562-3.636,2.42-5.845,2.42S7.981,19.672,6.422,18.111z" />
                            </g>
                        </svg>
                    </span>
                    <input type="text" id="mobile-search-input" class="form-control" placeholder="search your product"  name="search" @isset($search) value="{{$search}}" @endisset> 
                    <span class="input-group-text close-searchbar">
                        <svg viewBox="0 0 329.26933 329" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0" />
                        </svg>
                    </span>
                </div>
            </div>
        </form>
    </div>
    <!-- mid-header start-->

    <!-- end-header start-->
    <div class="category-header-2">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="navbar-menu">
                        <div class="logo-block">
                            <div class="brand-logo logo-sm-center">
                                <a href="{{ route('home') }}">
                                    <img src="{{ $site_settings->logo ? $site_settings->logo->getUrl('preview') : '' }}" class="img-fluid"
                                        alt="logo">
                                </a>
                            </div>
                        </div>
                        <div class="nav-block">

                            <div class="nav-left">

                                <nav class="navbar" data-bs-toggle="collapse"
                                    data-bs-target="#navbarToggleExternalContent">
                                    <button class="navbar-toggler" type="button">
                                        <span class="navbar-icon"><i class="fa fa-arrow-down"></i></span>
                                    </button>
                                    <h5 class="mb-0  text-white title-font"> {{ __('frontend.header.shoping_by_category') }} </h5>
                                </nav>
                                <div class="collapse  nav-desk" id="navbarToggleExternalContent">
                                    <ul class="nav-cat title-font">
                                        @foreach ($header_home_categories as $homecategory)
                                            <li>
                                                <a href="{{ route('frontend.products.category',$homecategory->category->slug) }}">{{ $homecategory->category->name ?? '' }}</a>
                                            </li>
                                        @endforeach
                                        {{-- <li>
                                            <a href="category-page.html" class="mor-slide-click">تصنيفات أخرى <i
                                                    class="fa fa-angle-down pro-down"></i><i
                                                    class="fa fa-angle-up pro-up"></i></a>
                                        </li> --}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="menu-block">
                            <nav id="main-nav">
                                <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
                                <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                                    <li>
                                        <div class="mobile-back text-right"><i class="fa fa-angle-left ps-2"
                                                aria-hidden="true"></i></div>
                                    </li>
                                    <!--HOME-->

                                    <!--SHOP-->
                                    <li>
                                        <a class="dark-menu-item" href="{{ route('home') }}"> {{ __('frontend.header.home') }}</a>
                                    </li>
                                    <li>
                                        <a class="dark-menu-item" href="{{ route('frontend.search') }}">{{ __('frontend.header.new_products') }} </a>
                                    </li>



                                    <!--pages meu start-->
                                    <li>
                                        <a class="dark-menu-item" href="javascript:void(0)"> {{ __('frontend.header.products') }}</a>
                                        <ul> 
                                            @foreach ($header_nested_categories as $category)
                                                <li>
                                                    <a  href="{{ route('frontend.products.category',$category->slug) }}">{{ $category->name }}</a>
                                                    <ul>
                                                        @foreach ($category->sub_categories->where('published',1) as $subcategory)
                                                            <li>
                                                                <a
                                                                    href="{{ route('frontend.products.subcategory', $subcategory->slug) }}">{{ $subcategory->name }}</a>
                                                                <ul>
                                                                    @foreach ($subcategory->sub_sub_categories->where('published',1) as $subsubcategory)
                                                                        <li><a
                                                                                href="{{ route('frontend.products.subsubcategory', $subsubcategory->slug) }}">{{ $subsubcategory->name }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <!--product-end end-->

                                    {{-- <li>
                                        <a class="dark-menu-item" href="{{ route('frontend.beseller') }}"> {{ __('frontend.header.be_seller') }}</a>
                                    </li>
                                    <li>
                                        <a class="dark-menu-item" href="{{ route('frontend.bedesigner') }}">{{ __('frontend.header.be_designer') }} </a>
                                    </li> --}}
                                    <li>

                                    <li>
                                        <a class="dark-menu-item" href="{{ route('frontend.contact') }}"> {{ __('frontend.header.contact_us') }} </a>
                                    </li>
                                    <li>
                                </ul>
                            </nav>
                        </div>
                        <div class="icon-block">
                            <ul>


                                <li class="mobile-search">
                                    <a href="javascript:void(0)">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 612.01 612.01"
                                            style="enable-background:new 0 0 612.01 612.01;" xml:space="preserve">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M606.209,578.714L448.198,423.228C489.576,378.272,515,318.817,515,253.393C514.98,113.439,399.704,0,257.493,0
                                                            C115.282,0,0.006,113.439,0.006,253.393s115.276,253.393,257.487,253.393c61.445,0,117.801-21.253,162.068-56.586
                                                            l158.624,156.099c7.729,7.614,20.277,7.614,28.006,0C613.938,598.686,613.938,586.328,606.209,578.714z M257.493,467.8
                                                            c-120.326,0-217.869-95.993-217.869-214.407S137.167,38.986,257.493,38.986c120.327,0,217.869,95.993,217.869,214.407
                                                            S377.82,467.8,257.493,467.8z" />
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                </li>
                                <li class="mobile-user onhover-dropdown">
                                    <a  @auth href="{{ route('frontend.dashboard') }}" @else href="{{ route('login') }}" @endauth> 
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                            xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path
                                                        d="M256,0c-74.439,0-135,60.561-135,135s60.561,135,135,135s135-60.561,135-135S330.439,0,256,0z M256,240
                                                        c-57.897,0-105-47.103-105-105c0-57.897,47.103-105,105-105c57.897,0,105,47.103,105,105C361,192.897,313.897,240,256,240z" />
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M297.833,301h-83.667C144.964,301,76.669,332.951,31,401.458V512h450V401.458C435.397,333.05,367.121,301,297.833,301z
                                                        M451.001,482H451H61v-71.363C96.031,360.683,152.952,331,214.167,331h83.667c61.215,0,118.135,29.683,153.167,79.637V482z" />
                                                </g>
                                            </g>
                                        </svg> 
                                    </a>
                                </li>

                                <li class="mobile-wishlist item-count" @auth onclick="openWishlist()" @endauth>
                                    <a @guest  href="{{ route('login') }}" @endguest>
                                        <svg viewBox="0 -28 512.001 512" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="m256 455.515625c-7.289062 0-14.316406-2.640625-19.792969-7.4375-20.683593-18.085937-40.625-35.082031-58.21875-50.074219l-.089843-.078125c-51.582032-43.957031-96.125-81.917969-127.117188-119.3125-34.644531-41.804687-50.78125-81.441406-50.78125-124.742187 0-42.070313 14.425781-80.882813 40.617188-109.292969 26.503906-28.746094 62.871093-44.578125 102.414062-44.578125 29.554688 0 56.621094 9.34375 80.445312 27.769531 12.023438 9.300781 22.921876 20.683594 32.523438 33.960938 9.605469-13.277344 20.5-24.660157 32.527344-33.960938 23.824218-18.425781 50.890625-27.769531 80.445312-27.769531 39.539063 0 75.910156 15.832031 102.414063 44.578125 26.191406 28.410156 40.613281 67.222656 40.613281 109.292969 0 43.300781-16.132812 82.9375-50.777344 124.738281-30.992187 37.398437-75.53125 75.355469-127.105468 119.308594-17.625 15.015625-37.597657 32.039062-58.328126 50.167969-5.472656 4.789062-12.503906 7.429687-19.789062 7.429687zm-112.96875-425.523437c-31.066406 0-59.605469 12.398437-80.367188 34.914062-21.070312 22.855469-32.675781 54.449219-32.675781 88.964844 0 36.417968 13.535157 68.988281 43.882813 105.605468 29.332031 35.394532 72.960937 72.574219 123.476562 115.625l.09375.078126c17.660156 15.050781 37.679688 32.113281 58.515625 50.332031 20.960938-18.253907 41.011719-35.34375 58.707031-50.417969 50.511719-43.050781 94.136719-80.222656 123.46875-115.617188 30.34375-36.617187 43.878907-69.1875 43.878907-105.605468 0-34.515625-11.605469-66.109375-32.675781-88.964844-20.757813-22.515625-49.300782-34.914062-80.363282-34.914062-22.757812 0-43.652344 7.234374-62.101562 21.5-16.441406 12.71875-27.894532 28.796874-34.609375 40.046874-3.453125 5.785157-9.53125 9.238282-16.261719 9.238282s-12.808594-3.453125-16.261719-9.238282c-6.710937-11.25-18.164062-27.328124-34.609375-40.046874-18.449218-14.265626-39.34375-21.5-62.097656-21.5zm0 0" />
                                        </svg>
                                        <div class="cart-item">

                                        </div>
                                        @auth
                                            <div class="item-count-contain">
                                                {{ auth()->user()->wishlists->count() }}
                                            </div>
                                        @endauth
                                    </a>
                                </li>
                                <li class="mobile-cart item-count" @if(!request()->is("cart*")) onclick="openCart()" @endif>
                                    <a  @if(request()->is("cart*"))  href="{{ route('frontend.cart') }}" @endif>
                                        
                                        <svg enable-background="new 0 0 512 512" viewBox="0 0 512 512"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="m497 401.667c-415.684 0-397.149.077-397.175-.139-4.556-36.483-4.373-34.149-4.076-34.193 199.47-1.037-277.492.065 368.071.065 26.896 0 47.18-20.377 47.18-47.4v-203.25c0-19.7-16.025-35.755-35.725-35.79l-124.179-.214v-31.746c0-17.645-14.355-32-32-32h-29.972c-17.64 0-31.99 14.351-31.99 31.99v31.594l-133.21-.232-9.985-54.992c-2.667-14.694-15.443-25.36-30.378-25.36h-68.561c-8.284 0-15 6.716-15 15s6.716 15 15 15c72.595 0 69.219-.399 69.422.719 16.275 89.632 5.917 26.988 49.58 306.416l-38.389.2c-18.027.069-32.06 15.893-29.81 33.899l4.252 34.016c1.883 15.06 14.748 26.417 29.925 26.417h26.62c-18.8 36.504 7.827 80.333 49.067 80.333 41.221 0 67.876-43.813 49.067-80.333h142.866c-18.801 36.504 7.827 80.333 49.067 80.333 41.22 0 67.875-43.811 49.066-80.333h31.267c8.284 0 15-6.716 15-15s-6.716-15-15-15zm-209.865-352.677c0-1.097.893-1.99 1.99-1.99h29.972c1.103 0 2 .897 2 2v111c0 8.284 6.716 15 15 15h22.276l-46.75 46.779c-4.149 4.151-10.866 4.151-15.015 0l-46.751-46.779h22.277c8.284 0 15-6.716 15-15v-111.01zm-30 61.594v34.416h-25.039c-20.126 0-30.252 24.394-16.014 38.644l59.308 59.342c15.874 15.883 41.576 15.885 57.452 0l59.307-59.342c14.229-14.237 4.13-38.644-16.013-38.644h-25.039v-34.254l124.127.214c3.186.005 5.776 2.603 5.776 5.79v203.25c0 10.407-6.904 17.4-17.18 17.4h-299.412l-35.477-227.039zm-56.302 346.249c0 13.877-11.29 25.167-25.167 25.167s-25.166-11.29-25.166-25.167 11.29-25.167 25.167-25.167 25.166 11.291 25.166 25.167zm241 0c0 13.877-11.289 25.167-25.166 25.167s-25.167-11.29-25.167-25.167 11.29-25.167 25.167-25.167 25.166 11.291 25.166 25.167z" />
                                            </g>
                                        </svg> 
                                        <div class="cart-item">
                                            <h5>{{ __('frontend.header.cart') }} </h5>
                                            <h5>{{ __('frontend.header.shoping') }}</h5>
                                        </div>  
                                        <div class="item-count-contain">
                                            {{ session('cart') ? session('cart')->count() : 0 }}
                                        </div> 
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <div class="category-right">
                            <div class="contact-block">
                                <div>
                                    <i class="fa fa-volume-control-phone"></i>
                                    <span>call us <span>{{ $site_settings->phone_number }}</span></span>
                                </div>
                            </div>
                            <div class="btn-group">
                                <div class="gift-block">

                                    <div class="grif-icon tooltip-top"
                                        data-tippy-content="اضف مناسباتك الخاصة طول العام "> <a href="{{ route('frontend.events') }}"> <i
                                                class="fa fa-calendar" aria-hidden="true"></i>
                                        </a>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ route('frontend.search') }}">
            <div class="searchbar-input">
                    <div class="input-group">
                        <span class="input-group-text"><svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28.931px"
                                height="28.932px" viewBox="0 0 28.931 28.932"
                                style="enable-background:new 0 0 28.931 28.932;" xml:space="preserve">
                                <g>
                                    <path
                                        d="M28.344,25.518l-6.114-6.115c1.486-2.067,2.303-4.537,2.303-7.137c0-3.275-1.275-6.355-3.594-8.672C18.625,1.278,15.543,0,12.266,0C8.99,0,5.909,1.275,3.593,3.594C1.277,5.909,0.001,8.99,0.001,12.266c0,3.276,1.275,6.356,3.592,8.674c2.316,2.316,5.396,3.594,8.673,3.594c2.599,0,5.067-0.813,7.136-2.303l6.114,6.115c0.392,0.391,0.902,0.586,1.414,0.586c0.513,0,1.024-0.195,1.414-0.586C29.125,27.564,29.125,26.299,28.344,25.518z M6.422,18.111c-1.562-1.562-2.421-3.639-2.421-5.846S4.86,7.983,6.422,6.421c1.561-1.562,3.636-2.422,5.844-2.422s4.284,0.86,5.845,2.422c1.562,1.562,2.422,3.638,2.422,5.845s-0.859,4.283-2.422,5.846c-1.562,1.562-3.636,2.42-5.845,2.42S7.981,19.672,6.422,18.111z" />
                                </g>
                            </svg></span> 
                                <input type="text" class="form-control" placeholder="search your product" name="search" @isset($search) value="{{$search}}" @endisset> 
                        <span class="input-group-text close-searchbar"><svg viewBox="0 0 329.26933 329"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0" />
                            </svg></span>
                    </div>
            </div>
        </form>

    </div>
    <!-- end-header start-->
</header>
