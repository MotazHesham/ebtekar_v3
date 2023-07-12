<!DOCTYPE html>
<html lang="en">

<head> 
    @php
        $site_settings = get_site_setting();
    @endphp 
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('meta_title', $site_settings->site_name )</title>
    <meta name="description" content="@yield('meta_description', $site_settings->description_seo)" />
    <meta name="keywords" content="@yield('meta_keywords', $site_settings->keywords_seo)">
    <meta name="author" content="{{ $site_settings->author_seo }}">
    <meta name="sitemap_link" content="{{ $site_settings->sitemap_link_seo }}"> 

    @yield('meta')
    <!--icons-->
    <link rel="icon" href="{{ $site_settings->logo ? $site_settings->logo->getUrl() : '' }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $site_settings->logo ? $site_settings->logo->getUrl() : '' }}" type="image/x-icon">

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
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/'. $site_settings->css_file_name) }}" media="screen" id="color"> 

    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/select2.min.css') }}">
    <style>
        .select2 {
            max-width: 100%;
            width: 100% !important;
        }
        
        .select2-selection__rendered {
            padding-bottom: 5px !important;
        }
        
        .select2-results__option {
            padding-left: 0px;
            padding-right: 0px;
        }
        .invalid-feedback{
            display: block;
        }
        .product-notification span{
            text-decoration: line-through;
        }
    </style>
    @yield('styles')

    @if(app()->isProduction())
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-232371041-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-232371041-1');
        </script>
        
        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '201006871905666');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=201006871905666&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Code -->
            
        <script>
            !function (w, d, t) {
            w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++
                )ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src=i+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};

                ttq.load('CABJM03C77U5A93227KG');
                ttq.page();
            }(window, document, 'ttq');
        </script>
    @endif
</head>

<body class="bg-light rtl">

    

    <!-- loader start -->
    <div class="loader-wrapper">
        <div>
            <img src="{{  $site_settings->logo ? $site_settings->logo->getUrl() : '' }}" alt="loader">
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

    @if(app()->isProduction())
        <div id="fb-root"></div>
        <!-- Your customer chat code -->
        <div class="fb-customerchat"
            attribution=setup_tool
            page_id="347168479120192">
        </div>
    @endif


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
                <h3>{{ trans('frontend.cart.shoping_cart') }}</h3>
                <div class="close-cart">
                    <a href="javascript:void(0)" onclick="closeCart()">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="cart_media">
                @include('frontend.partials.cart_nav')
            </div>
        </div>
    </div>
    <!-- Add to cart bar end-->

    <!-- wishlistbar bar -->
    <div id="wishlist_side" class="add_to_cart right ">
        <a href="javascript:void(0)" class="overlay" onclick="closeWishlist()"></a>
        <div class="cart-inner">
            <div class="cart_top">
                <h3>{{ trans('frontend.app.favorites') }}</h3>
                <div class="close-cart">
                    <a href="javascript:void(0)" onclick="closeWishlist()">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="cart_media">
                <ul class="cart_product">
                    @auth
                        @foreach(auth()->user()->wishlists()->with('product')->orderBy('created_at','desc')->get()->take(10) as $wishlist)
                            @php
                                $image = '';
                                if($wishlist->product && $wishlist->product->photos != null){
                                    $image = isset($wishlist->product->photos[0]) ? $wishlist->product->photos[0]->getUrl('preview2') : '';
                                }
                                $prices = product_price_in_cart(1,null,$wishlist->product);

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
                                                <?php echo $wishlist->product->calc_price_as_text(); ?>
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
                            <a href="{{ route('frontend.wishlist')}}" class="btn btn-solid btn-block btn-md">{{ trans('frontend.app.show_favorites') }}</a>
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

    <!-- flash Deal product -->
    @php
        $flash_deal_product = \App\Models\Product::where('flash_deal',1)->where('published',1)->inRandomOrder()->first();
    @endphp
    @if($flash_deal_product) 
        @php
            $flash_deal_img =  isset($flash_deal_product->photos[0]) ? $flash_deal_product->photos[0]->getUrl('preview') : '';
        @endphp
        <div class="product-notification" id="dismiss">
            <span onclick="dismiss();" class="btn-close" aria-hidden="true"></span>
            <a href="{{ route('frontend.product',$flash_deal_product->slug) }}">
                <div class="media">
                    <img class="me-2" src="{{ $flash_deal_img }}" alt="">
                    <div class="media-body" style="color:black">
                        <h5 class="mt-0 mb-1">{{ $flash_deal_product->name }}</h5> 
                        <?php echo $flash_deal_product->calc_price_as_text(); ?>  
                    </div>
                </div>
            </a>
        </div>  
    @endif
    <!-- flash Deal product -->

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


    
    <script src="{{ asset('dashboard_offline/js/select2.full.min.js') }}"></script>
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

    <script>
        
        @if(app()->isProduction())
            // messanger chatpopup
            $(document).ready(function() { 
                window.fbAsyncInit = function() {
                    FB.init({
                        xfbml            : true,
                        version          : 'v3.3'
                    });
                    };
                
                    (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
            });
        @endif


        function showAlert(type, title, message) {
            swal({
                title: title,
                text: message,
                type: type,
                showConfirmButton: 'Okay',
                timer: 3000
            });
        } 
        
        $('.select2').select2()
        $('.select-all').click(function () {
            let $select2 = $(this).parent().siblings('.select2')
            $select2.find('option').prop('selected', 'selected')
            $select2.trigger('change')
        })
        $('.deselect-all').click(function () {
            let $select2 = $(this).parent().siblings('.select2')
            $select2.find('option').prop('selected', '')
            $select2.trigger('change')
        })
    </script>
    <script>

        //perevent submittig multiple times
        $("body").on("submit", "form", function() {
            $(this).submit(function() {
                return false;
            });
            return true;
        });
        
        var photo_id = 2;
        function add_more_slider_image(){
            var photoAdd =  '<div class="row">'; 
            photoAdd += '<div class="col-md-1">';
            photoAdd += '<button type="button" onclick="delete_this_row(this)" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
            photoAdd += '</div>';
            photoAdd += '<div class="col-md-7 mb-3">';
            photoAdd += '<input type="file" id="photos-'+photo_id+'" name="photos[]" class="form-control" multiple accept="image/*" />'; 
            photoAdd += '</div>';
            photoAdd += '<div class="col-md-4 mb-3">';
            photoAdd += '<input type="text" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة" >';
            photoAdd += '</div>';
            photoAdd += '</div>'; 
            $('#product-images').append(photoAdd);

            photo_id++; 
        } 
        function delete_this_row(em){
            $(em).closest('.row').remove();
        }

        function quick_view(id){
            $('#quick-view .modal-body div').html(null);
            $.post('{{ route('frontend.product.quick_view') }}', {_token:'{{ csrf_token() }}',id:id}, function(data){
                $('#quick-view .modal-body div').html(data);
            });
        }

        function updateCartItem(id,increment,elem){
            let quantity = parseInt($('#' + elem + id).val()) + increment;
            if(quantity > 0){
                $('#mob-cart-qty-'+id).html(quantity + 'x');  
                $.post('{{ route('frontend.cart.update') }}', {_token:'{{ csrf_token() }}',id:id,quantity:quantity}, function(data){
                    $('#cart_side .cart_total .total span').html(data.total_cost);
                    $('#td-total-'+id).html(data.cartIteam_total);
                    $('#td-commission-'+id + ' b').html(data.cartIteam_commission);
                    $('#td-total-cost').html(data.total_cost);
                });
            }
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
                    $('#product-commission-for-variant').html(data.commission);
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
