@extends('frontend.layout.app')

@section('content')


    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>المنتجات</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)">المنتجات</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- section start -->
    <section class="section-big-pt-space b-g-light">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 position-relative">
                        <div class="product-slick no-arrow"> 
                            @foreach($product->photos as $key => $media)
                                <div><img src="{{ $media->getUrl() }}" alt=""
                                        class="img-fluid  image_zoom_cls-{{$key}}"></div>
                            @endforeach 
                        </div>
                        <div class="row">
                            <div class="col-12 p-0">
                                <div class="slider-nav"> 
                                    @foreach($product->photos as $key => $media)
                                        <div><img src="{{ $media->getUrl('preview2') }}" alt=""
                                                class="img-fluid  image_zoom_cls-{{$key}}"></div>
                                    @endforeach 
                                </div>
                            </div>
                        </div>
                        <div class="image-360" data-bs-toggle="modal" data-bs-target="#view360">
                            <img src="{{ asset('frontend/assets/images/360deg.png') }}" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="col-lg-7 rtl-text">
                        <div class="product-right">
                            <div class="pro-group">
                                <h2>{{ $product->name }}</h2>
                                <ul class="pro-price" id="">
                                    @if($product->discount > 0)
                                        <li id="product-price-for-variant">{{ front_currency($product->unit_price)}}</li>
                                        <li><span id="product-price-calc-discount">{{ front_currency($product->calc_discount($product->unit_price))}}</span></li>
                                    @else
                                        <li id="product-price-for-variant">{{ front_currency($product->unit_price)}}</li>
                                    @endif
                                </ul>
                                <div class="revieu-box">
                                    <ul>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star-o"></i></li>
                                    </ul>
                                    <a href="review.html"><span>({{$product->reviews()->count()}} تعليقات)</span></a>
                                </div>

                            </div>
                            <form id="add-to-cart-form" action="{{route('frontend.cart.add')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{$product->id}}">
                                <input type="hidden" name="variant" id="variant">
                                <div id="selectSize"
                                    class="pro-group addeffect-section product-description border-product mb-0">
                                    @if ($product->attribute_options != null && count(json_decode($product->attribute_options)) > 0)
                                        @foreach (json_decode($product->attribute_options) as $key => $attr_optn)
                                            @php
                                                $attribute = \App\Models\Attribute::find($attr_optn->attribute_id);
                                            @endphp
                                            <h6 class="product-title size-text"> {{ $attribute ? $attribute->name : ''}} <span>
                                                </span></h6>
                                            <div class="size-box" id="{{$key}}-{{$attr_optn->attribute_id}}">
                                                <ul>
                                                    @foreach ($attr_optn->values as $key2 => $value)
                                                        <li data-attribute="{{$key}}-{{$attr_optn->attribute_id}}" @if($key2 == 0) class="active" @endif  style="width: fit-content;">
                                                            <input style="display: none" type="radio" id="{{ $attr_optn->attribute_id }}-{{ $value }}" name="attribute_{{ $attr_optn->attribute_id }}" value="{{ $value }}" @if($key2 == 0) checked @endif>
                                                            <label style="width:100%;height:100%;user-select: none;padding: 6px 12px;" for="{{ $attr_optn->attribute_id }}-{{ $value }}">{{ $value }}</label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    @endif

                                    @if ($product->colors != null && !empty(json_decode($product->colors)))

                                        <h6 class="product-title">اللون</h6>
                                        <div class="color-selector inline">
                                            <ul>
                                                @if (count(json_decode($product->colors)) > 0)
                                                    @foreach (json_decode($product->colors) as $key => $color)
                                                        <li>
                                                            <input style="display:none" type="radio" id="{{ $product->id }}-color-{{ $key }}" name="color" value="{{ $color }}" @if($key == 0) checked @endif>
                                                            <label style="background: {{ $color }};" for="{{ $product->id }}-color-{{ $key }}" data-toggle="tooltip" @if($key == 0) class="active" @endif>

                                                            </label>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="pro-group">
                                        <a href="" data-bs-toggle="modal" data-bs-target="#requist">اطلب منتجك الخاص </a>
                                        <div class="modal fade" id="requist" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">اطلب منتجك الخاص</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="col-12 mb-3">
                                                            <label for="name">الصور المراد طباعتها علي المنتج </label>
                                                            <input type="file" id="myfile" name="myfile"
                                                                class="form-control">


                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <label for="name">الجملة المراد كتابتها على الصورة</label>
                                                            <input type="text" name="note" class="form-control" id="name"
                                                                placeholder="الجملة المراد كتابتها على الصورة" >
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <label>وصف</label>
                                                            <textarea class="form-control" name="desc" placeholder="وصف" rows="3"></textarea>
                                                        </div>


                                                        <a href="javascript:void(0)" class="btn btn-rounded black-btn me-3">ارسال
                                                            الطلب</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h6 class="product-title">العدد</h6>
                                    <div class="qty-box">
                                        <div class="input-group">
                                            <button class="qty-minus" type="button"></button>
                                            <input class="qty-adj form-control" type="number" value="1" name="quantity" id="available-quantity-input"/>
                                            <button class="qty-plus" type="button"></button>
                                        </div>
                                        &nbsp;&nbsp;
                                        <b>(متاح <span id="available-quantity-span">{{$product->current_stock}}</span>)</b>
                                    </div>

                                    <div class="product-buttons">
                                        <button type="submit" id="cartEffect"
                                            class="btn cart-btn btn-normal tooltip-top" data-tippy-content="Add to cart">
                                            <i class="fa fa-shopping-cart"></i>
                                            أضف الى السلة
                                        </button>
                                        <a href="{{ route('frontend.wishlist.add',$product->slug) }}" class="btn btn-normal add-to-wish tooltip-top"
                                            data-tippy-content="Add to wishlist">
                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                        </a>
                                    </div>

                                </div>
                            </form>
                            <div class="pro-group">
                                <h6 class="product-title">تفاصيل المنتج</h6>
                                <p>
                                    <?php echo $product->description; ?>
                                </p>
                            </div>

                            <div class="pro-group pb-0">
                                <h6 class="product-title">مشاركة</h6>
                                {{-- <ul class="product-social">
                                    <li><a href="javascript:void(0)"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-google-plus"></i></a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-instagram"></i></a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-rss"></i></a></li>
                                </ul> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section ends -->


    <!-- product-tab starts -->
    <section class=" tab-product  tab-exes ">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-lg-12 ">
                    <div class=" creative-card creative-inner">
                        <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab"
                                    href="#top-home" role="tab" aria-selected="true">التفاصيل</a>
                                <div class="material-border"></div>
                            </li>
                            <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                    href="#top-contact" role="tab" aria-selected="false">فيديو</a>
                                <div class="material-border"></div>
                            </li>
                            <li class="nav-item"><a class="nav-link" id="review-top-tab" data-bs-toggle="tab"
                                    href="#top-review" role="tab" aria-selected="false">التعليقات</a>
                                <div class="material-border"></div>
                            </li>
                        </ul>
                        <div class="tab-content nav-material" id="top-tabContent">
                            <div class="tab-pane fade show active" id="top-home" role="tabpanel"
                                aria-labelledby="top-home-tab">
                                <p>
                                    <?php echo $product->description; ?>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="top-contact" role="tabpanel"
                                aria-labelledby="contact-top-tab">
                                <div class="mt-4 text-center">
                                    <!-- 16:9 aspect ratio -->
                                    <div class="embed-responsive embed-responsive-16by9 mb-5">
                                        @if ($product->video_provider == 'youtube' && $product->video_link != null)
                                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ explode('=', $product->video_link)[1] ?? '' }}"></iframe>
                                        @elseif ($product->video_provider == 'dailymotion' && $product->video_link != null)
                                            <iframe class="embed-responsive-item" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $product->video_link)[1] ?? '' }}"></iframe>
                                        @elseif ($product->video_provider == 'vimeo' && $product->video_link != null)
                                            <iframe src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $product->video_link)[1] ?? '' }}" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                                <form class="theme-form">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="media">
                                                <label>التقييم</label>
                                                <div class="media-body ms-3">
                                                    <div class="rating three-star"><i class="fa fa-star"></i> <i
                                                            class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                            class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="name">الاسم</label>
                                            <input type="text" class="form-control" id="name"
                                                placeholder="ادخل اسمك" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>البريد الإلكتروني</label>
                                            <input type="text" class="form-control"
                                                placeholder="ادخل بريدك الإلكتروني" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label>عنوان التقييم</label>
                                            <input type="text" class="form-control" placeholder="عنوان التقييم"
                                                required>
                                        </div>
                                        <div class="col-md-12">
                                            <label>التقييم</label>
                                            <textarea class="form-control" placeholder="اكتب تقييمك هنا" rows="6"></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-normal" type="submit">إرسال</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product-tab ends -->

    <!-- related products -->
    <section class="section-big-py-space  ratio_asos b-g-light product" style="direction: ltr;">
        <div class="container">
            <div class="row">
                <div class="col-12 product-related">
                    <h2>منتجات مشابه</h2>
                </div>
            </div>
            <div class="row">
                <div class="col pr-0">
                    <div class="product-slide-5 product-m no-arrow">
                        @foreach (\App\Models\Product::where('sub_category_id', $product->sub_category_id)->where('id', '!=', $product->id)->where('published', '1')->limit(10)->get() as $key => $related_product)
                            
                            @php
                                $front_imag_2 = $related_product->photos[0] ? $related_product->photos[0]->getUrl('preview2') : '';
                                $back_imag_2 = $related_product->photos[1] ? $related_product->photos[1]->getUrl('preview2') : $front_imag_2;
                            @endphp 

                            <div>
                                <div class="product-box product-box2">
                                    <div class="product-imgbox">
                                        <div class="product-front">
                                            <a href="{{ route('frontend.product',$related_product->slug) }}">
                                                <img src="{{ $front_imag_2 }}"
                                                    class="img-fluid" alt="product">
                                            </a>
                                        </div>
                                        <div class="product-back">
                                            <a href="{{ route('frontend.product',$related_product->slug) }}">
                                                <img src="{{ $back_imag_2 }}"
                                                    class="img-fluid" alt="product">
                                            </a>
                                        </div>
                                        <div class="product-icon icon-inline">
                                            <button class="tooltip-top add-cartnoty" data-tippy-content="Add to cart">
                                                <i data-feather="shopping-cart"></i>
                                            </button>
                                            <a href="javascript:void(0)" class="add-to-wish tooltip-top"
                                                data-tippy-content="Add to Wishlist">
                                                <i data-feather="heart"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="quick_view('{{$product->id}}')" data-bs-toggle="modal" data-bs-target="#quick-view"
                                                class="tooltip-top" data-tippy-content="Quick View">
                                                <i data-feather="eye"></i>
                                            </a>

                                        </div>
                                        {{-- <div class="new-label1">
                                            <div>new</div>
                                        </div> --}}
                                        {{-- <div class="on-sale1">
                                            on sale
                                        </div> --}}
                                    </div>
                                    <div class="product-detail product-detail2 ">
                                        <ul>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star-o"></i></li>
                                        </ul>
                                        <a href="{{ route('frontend.product',$related_product->slug) }}">
                                            <h3> {{ $related_product->name }} </h3>
                                        </a>
                                        <h5>
                                            @if($related_product->discount > 0)
                                                {{front_currency($related_product->calc_discount($product->unit_price))}}
                                                <span>
                                                    {{ front_currency($related_product->unit_price) }}
                                                </span>
                                            @else
                                                {{ front_currency($related_product->unit_price) }}
                                            @endif
                                        </h5>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- related products -->
@endsection

@section('scripts')
@parent
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();

        $('#add-to-cart-form input').on('change', function(){
            getVariantPrice();
        });
        });
    </script>
@endsection
