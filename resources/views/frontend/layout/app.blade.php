<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ebtekar</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('frontend/assets/images/favicon/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('frontend/assets/images/favicon/favicon.png') }}" type="image/x-icon">

    <!--Google font-->
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- icons fonts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/themify.css') }}">

    <!--Slick slider css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/slick-theme.css') }}">

    <!--Animate css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/animate.css') }}">
    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/bootstrap.css') }}">

    <!-- Theme css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/color3.css') }}" media="screen" id="color">

    <style>
        .invalid-feedback{
            display: block;
        }
    </style>


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-232371041-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-232371041-1');
    </script>

</head>

<body class="bg-light rtl">

    @php
        $home_general_setting = \App\Models\GeneralSetting::first();
    @endphp

    <!-- loader start -->
    <div class="loader-wrapper">
        <div>
            <img src="{{  $home_general_setting->logo->getUrl() }}" alt="loader">
        </div>
    </div>
    <!-- loader end -->


    <!-- header start-->
    @include('frontend.layout.header')
    <!-- header end-->


    @yield('content')


    <!-- footer start -->
    @include('frontend.layout.footer')
    <!-- footer end -->


    <!-- Quick-view modal popup start-->
    <div class="modal fade bd-example-modal-lg theme-modal" id="quick-view" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content quick-view-modal">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div>
                        {{-- ajax call --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick-view modal popup end-->


    <!-- edit product modal start-->
    <div class="modal fade bd-example-modal-lg theme-modal pro-edit-modal" id="edit-product" tabindex="-1"
        role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    <div class="pro-group">
                        <div class="product-img">
                            <div class="media">
                                <div class="img-wraper">
                                    <a href="product-page.html">
                                        <img src="{{ asset('frontend/assets/images/product/7.jpg') }}" alt="" class="img-fluid">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <a href="product-page.html">
                                        <h3>بوكس بالاسم</h3>
                                    </a>
                                    <h6>le 80<span>le 120</span></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pro-group">
                        <h6 class="product-title">المقاس</h6>
                        <div class="size-box">
                            <ul>
                                <li><a href="javascript:void(0)">10*12</a></li>
                                <li><a href="javascript:void(0)">16*20</a></li>
                                <li><a href="javascript:void(0)">15*15</a></li>

                            </ul>
                        </div>
                    </div>
                    <div class="pro-group">
                        <h6 class="product-title">اختر اللون</h6>
                        <div class="color-selector inline">
                            <ul>
                                <li>
                                    <div class="color-1 active"></div>
                                </li>
                                <li>
                                    <div class="color-2"></div>
                                </li>
                                <li>
                                    <div class="color-3"></div>
                                </li>
                                <li>
                                    <div class="color-4"></div>
                                </li>
                                <li>
                                    <div class="color-5"></div>
                                </li>
                                <li>
                                    <div class="color-6"></div>
                                </li>
                                <li>
                                    <div class="color-7"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="pro-group">
                        <h6 class="product-title">الكمية</h6>
                        <div class="qty-box">
                            <div class="input-group">
                                <button class="qty-minus"></button>
                                <input class="qty-adj form-control" type="number" value="1" />
                                <button class="qty-plus"></button>
                            </div>
                        </div>
                    </div>
                    <div class="pro-group mb-0">
                        <div class="modal-btn">
                            <a href="cart.html" class="btn btn-solid btn-sm">
                                اضف الى السلة
                            </a>
                            <a href="product-page.html" class="btn btn-solid btn-sm">
                                المزيد من التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- edit product modal end-->

    <!-- Add to cart bar -->
    <div id="cart_side" class="add_to_cart right ">
        <a href="javascript:void(0)" class="overlay" onclick="closeCart()"></a>
        <div class="cart-inner">
            <div class="cart_top">
                <h3>سلة التسوق</h3>
                <div class="close-cart">
                    <a href="javascript:void(0)" onclick="closeCart()">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="cart_media">
                <ul class="cart_product">
                    @auth
                        @php
                            $total = 0;
                        @endphp
                        @foreach(auth()->user()->carts()->with('product')->orderBy('created_at','desc')->get()->take(10) as $cartItem)
                            @php
                                $image = '';
                                if($cartItem->product && $cartItem->product->photos != null){ 
                                    $image = $cartItem->product->photos[0] ? $cartItem->product->photos[0]->getUrl('preview2') : '';
                                }
                                $total += $cartItem->total_cost;
                            @endphp

                            <li class="cart-{{ $cartItem->id }}">
                                <div class="media">
                                    <a {{ route('frontend.product',$cartItem->product->slug)}}>
                                        <img alt="megastore1" class="me-3" src="{{ $image }}">
                                    </a>
                                    <div class="media-body">
                                        <a {{ route('frontend.product',$cartItem->product->slug)}}>
                                            <h4>{{ $cartItem->product->name }}</h4>
                                        </a>
                                        <h6>
                                            {{ front_currency($cartItem->price) }}
                                        </h6>
                                        <div class="addit-box">
                                            <div class="qty-box">
                                                <div class="input-group">
                                                    <button class="qty-minus" onclick="updateCartItem('{{$cartItem->id}}',-1,'cart-qty-')"></button>
                                                    <input class="qty-adj form-control" onchange="updateCartItem('{{$cartItem->id}}',0,'cart-qty-')" type="number" id="cart-qty-{{$cartItem->id}}"  value="{{ $cartItem->quantity }}" min="1"/>
                                                    <button class="qty-plus" onclick="updateCartItem('{{$cartItem->id}}',1,'cart-qty-')"></button>
                                                </div>
                                            </div>
                                            <div class="pro-add">
                                                {{-- <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#edit-product">
                                                    <i data-feather="edit"></i>
                                                </a> --}}
                                                <a href="javascript:void(0)" onclick="deleteCartItem('{{$cartItem->id}}')">
                                                    <i data-feather="trash-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endauth
                </ul>
                <ul class="cart_total">
                    <li>
                        <div class="total">
                            الإجمالي
                            @auth
                                <span>
                                    {{ front_currency($total) }}
                                </span>
                            @endauth
                        </div>
                    </li>
                    <li>
                        <div class="buttons">
                            <a href="{{ route('frontend.cart') }}" class="btn btn-solid btn-sm">عرض السلة</a>
                            <a href="{{ route('frontend.payment_select') }}" class="btn btn-solid btn-sm ">الدفع</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Add to cart bar end-->

    <!-- wishlistbar bar -->
    <div id="wishlist_side" class="add_to_cart right ">
        <a href="javascript:void(0)" class="overlay" onclick="closeWishlist()"></a>
        <div class="cart-inner">
            <div class="cart_top">
                <h3>المفضلة</h3>
                <div class="close-cart">
                    <a href="javascript:void(0)" onclick="closeWishlist()">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="cart_media">
                <ul class="cart_product">
                    @auth
                        @foreach(auth()->user()->wishlists()->orderBy('created_at','desc')->get()->take(10) as $wishlist)
                            @php
                                $image = '';
                                if($wishlist->product && $wishlist->product->photos != null){
                                    $image = $wishlist->product->photos[0] ? $wishlist->product->photos[0]->getUrl('preview2') : '';
                                }
                            @endphp
                                <li class="cart-{{ $wishlist->id }}">
                                    <div class="media">
                                        <a {{ route('frontend.product',$wishlist->product->slug)}}>
                                            <img alt="megastore1" class="me-3" src="{{ $image }}">
                                        </a>
                                        <div class="media-body">
                                            <a {{ route('frontend.product',$wishlist->product->slug)}}>
                                                <h4>{{ $wishlist->product->name }}</h4>
                                            </a>
                                            <h6>
                                                @if($wishlist->product->discount > 0)
                                                    {{front_currency($wishlist->product->calc_discount($wishlist->product->unit_price))}}
                                                    <span>
                                                        {{ front_currency($wishlist->product->unit_price) }}
                                                    </span>
                                                @else
                                                    {{ front_currency($wishlist->product->unit_price) }}
                                                @endif
                                            </h6>
                                            <div class="addit-box">
                                                <div class="pro-add">
                                                    <a href="javascript:void(0)" onclick="deleteWishlistItem('{{$wishlist->id}}')">
                                                        <i data-feather="trash-2"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                        @endforeach
                    @endauth
                </ul>
                <ul class="cart_total">
                    <li>
                        <div class="buttons">
                            <a href="{{ route('frontend.wishlist')}}" class="btn btn-solid btn-block btn-md">عرض قائمتي المفضلة</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- wishlistbar bar end-->

    <!-- My account bar start-->
    <div id="myAccount" class="add_to_cart right account-bar">
        <a href="javascript:void(0)" class="overlay" onclick="closeAccount()"></a>
        <div class="cart-inner">
            <div class="cart_top">
                <h3>حسابي</h3>
                <div class="close-cart">
                    <a href="javascript:void(0)" onclick="closeAccount()">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <form class="theme-form">
                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <input type="text" class="form-control" id="email" placeholder="البريد الالكتروني"
                        required="">
                </div>
                <div class="form-group">
                    <label for="review">كلمة المرور</label>
                    <input type="password" class="form-control" id="review" placeholder="ادخل كلمة المرور "
                        required="">
                </div>
                <div class="form-group">
                    <a href="javascript:void(0)" class="btn btn-solid btn-md btn-block ">دخول</a>
                </div>
                <div class="accout-fwd">
                    <a href="forget-pwd.html" class="d-block">
                        <h5>نسيت كلمة المرور؟</h5>
                    </a>
                    <a href="register.html" class="d-block">
                        <h6>ليس لديك حساب؟<span>مستخدم جديد</span></h6>
                    </a>
                </div>
            </form>
        </div>
    </div>
    <!-- Add to account bar end-->

    <!-- notification product -->
    {{-- <div class="product-notification" id="dismiss">
        <span onclick="dismiss();" class="btn-close" aria-hidden="true"></span>
        <div class="media">
            <img class="me-2" src="{{ asset('frontend/assets/images/product/2.jpg') }}" alt="">
            <div class="media-body">
                <h5 class="mt-0 mb-1">Flash deal</h5>
                هذا النص هو مثال ، لقد تم توليد هذا النص من مولد النص العربى
            </div>
        </div>
    </div> --}}
    <!-- notification product -->

    @include('sweetalert::alert')

    <!-- latest jquery-->
    <script src="{{ asset('frontend/assets/js/jquery-3.3.1.min.js') }}"></script>

    <!-- slick js-->
    <script src="{{ asset('frontend/assets/js/slick.js') }}"></script>



    <!-- tool tip js -->
    <script src="{{ asset('frontend/assets/js/tippy-popper.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/tippy-bundle.iife.min.js') }}"></script>

    <!-- popper js-->
    <script src="{{ asset('frontend/assets/js/popper.min.js') }}"></script>

    <!-- menu js-->
    <script src="{{ asset('frontend/assets/js/menu.js') }}"></script>

    <!-- father icon -->
    <script src="{{ asset('frontend/assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/feather-icon.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('frontend/assets/js/bootstrap.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('frontend/assets/js/bootstrap-notify.min.js') }}"></script>

    <!-- Theme js-->
    <script src="{{ asset('frontend/assets/js/script.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/modal.js') }}"></script>

    <script>

        function quick_view(id){
            $('#quick-view .modal-body div').html(null);
            $.post('{{ route('frontend.product.quick_view') }}', {_token:'{{ csrf_token() }}',id:id}, function(data){
                $('#quick-view .modal-body div').html(data);
            });
        }

        function updateCartItem(id,increment,elem){
            let quantity = parseInt($('#' + elem + id).val()) + increment;
            if(quantity > 0){
                $.post('{{ route('frontend.cart.update') }}', {_token:'{{ csrf_token() }}',id:id,quantity:quantity}, function(data){
                    $('#cart_side .cart_total .total span').html(data.total_cost);
                    $('#td-total-'+id).html(data.cartIteam_total);
                    $('#td-total-cost').html(data.total_cost);
                });
            }
        }

        function deleteCartItem(id){

            $.post('{{ route('frontend.cart.delete') }}', {_token:'{{ csrf_token() }}',id:id}, function(data){
                $('#cart_side .cart-'+id).fadeOut(150, function(){ $(this).remove();});
                $('#tr-cart-'+id).fadeOut(150, function(){ $(this).remove();});
                $('#cart_side .cart_total .total span').html(data);
                $('#td-total-cost').html(data);
            });
        }

        function deleteWishlistItem(id){

            $.post('{{ route('frontend.wishlist.delete') }}', {_token:'{{ csrf_token() }}',id:id}, function(data){
                $('#wishlist_side .cart-'+id).fadeOut(150, function(){ $(this).remove();});
                $('#wishlist-tr-'+id).fadeOut(150, function(){ $(this).remove();});
            });
        }

        function getVariantPrice(){
            $.ajax({
                type:"POST",
                url: '{{ route('frontend.product.variant_price') }}',
                data: $('#add-to-cart-form').serializeArray(),
                success: function(data){
                    if(data.discount > 0){
                        $('#product-price-calc-discount').html(data.before_discount);
                    }
                    $('#product-commission').html(data.commission);
                    $('#product-price-for-variant').html(data.price);
                    $('#add-to-cart-form #variant').val(data.variant);
                    $('#add-to-cart-form #available-quantity-span').html(data.available_quantity);
                    $('#add-to-cart-form #available-quantity-input').prop('max', data.available_quantity);
                }
            });
        }

    </script>

    @yield('scripts')
</body>

</html>
