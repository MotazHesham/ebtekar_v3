@extends('frontend.layout.app')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2> متابعة الطلب</h2>
                            <ul>
                                <li><a href="index.html">الرئيسية</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)"> متابعة الطلبات</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->


    <!--order tracking start-->
    <section class="order-tracking section-big-my-space  ">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div id="msform">
                        <!-- progressbar -->
                        <ul id="progressbar">
                            <li class="active">
                                <div class="icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                        xml:space="preserve">
                                        <g>
                                            <g>
                                                <path
                                                    d="M482,181h-31v-45c0-37.026-27.039-67.672-62.366-73.722C382.791,44.2,365.999,31,346,31H166 c-19.999,0-36.791,13.2-42.634,31.278C88.039,68.328,61,98.974,61,136v45H30c-16.569,0-30,13.431-30,30c0,16.567,13.431,30,30,30 h452c16.569,0,30-13.433,30-30C512,194.431,498.569,181,482,181z M421,181H91v-45c0-20.744,14.178-38.077,33.303-43.264 C130.965,109.268,147.109,121,166,121h180c18.891,0,35.035-11.732,41.697-28.264C406.822,97.923,421,115.256,421,136V181z" />
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M33.027,271l24.809,170.596C60.648,464.066,79.838,481,102.484,481h307.031c22.647,0,41.837-16.934,44.605-39.111 L478.973,271H33.027z M151,406c0,8.291-6.709,15-15,15s-15-6.709-15-15v-90c0-8.291,6.709-15,15-15s15,6.709,15,15V406z M211,406 c0,8.291-6.709,15-15,15s-15-6.709-15-15v-90c0-8.291,6.709-15,15-15s15,6.709,15,15V406z M271,406c0,8.291-6.709,15-15,15 c-8.291,0-15-6.709-15-15v-90c0-8.291,6.709-15,15-15s15,6.709,15,15V406z M331,406c0,8.291-6.709,15-15,15 c-8.291,0-15-6.709-15-15v-90c0-8.291,6.709-15,15-15c8.291,0,15,6.709,15,15V406z M391,406c0,8.291-6.709,15-15,15 c-8.291,0-15-6.709-15-15v-90c0-8.291,6.709-15,15-15c8.291,0,15,6.709,15,15V406z" />
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <span>الشحن</span>
                            </li>
                            <li>
                                <div class="icon">
                                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                        <g id="_01-home" data-name="01-home">
                                            <g id="glyph">
                                                <path
                                                    d="m256 4c-108.075 0-196 87.925-196 196 0 52.5 31.807 119.92 94.537 200.378a1065.816 1065.816 0 0 0 93.169 104.294 12 12 0 0 0 16.588 0 1065.816 1065.816 0 0 0 93.169-104.294c62.73-80.458 94.537-147.878 94.537-200.378 0-108.075-87.925-196-196-196zm0 336c-77.2 0-140-62.8-140-140s62.8-140 140-140 140 62.8 140 140-62.8 140-140 140z" />
                                                <path
                                                    d="m352.072 183.121-88-80a12 12 0 0 0 -16.144 0l-88 80a12.006 12.006 0 0 0 -2.23 15.039 12.331 12.331 0 0 0 10.66 5.84h11.642v76a12 12 0 0 0 12 12h28a12 12 0 0 0 12-12v-44a12 12 0 0 1 12-12h24a12 12 0 0 1 12 12v44a12 12 0 0 0 12 12h28a12 12 0 0 0 12-12v-76h11.642a12.331 12.331 0 0 0 10.66-5.84 12.006 12.006 0 0 0 -2.23-15.039z" />
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <span>العنوان</span>
                            </li>

                        </ul>
                        <!-- fieldsets -->
                        <fieldset>
                            <div class="container p-0">
                                <div class="row shpping-block">
                                    <div class="col-lg-8">
                                        <div class="order-tracking-contain order-tracking-box">
                                            <div class="tracking-group">
                                                <div class="delevery-code">
                                                    <h4>التوصيل</h4>
                                                    <a href="#" class="btn btn-solid btn-outline btn-md">تغيير
                                                        العنوان</a>
                                                </div>
                                            </div>
                                            <div class="tracking-group">
                                                <div class="product-offer">

                                                    <div class="offer-contain">
                                                        <ul>
                                                            <li>
                                                                <div>
                                                                    <h5>وسط البلد</h5>
                                                                    <p>515 شارع مراد الجيزة </p>
                                                                </div>
                                                            </li>
                                                        </ul>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tracking-group pb-0">
                                                <h4 class="tracking-title">المنتجات المطلوبة</h4>
                                                <ul class="may-product">
                                                    <li>
                                                        <div class="media">
                                                            <img src="assets/images/layout-2/product/1.jpg"
                                                                class="img-fluid" alt="">
                                                            <div class="media-body">
                                                                <h3>مج</h3>
                                                                <h4>le 70<span>le100</span></h4>
                                                                <h6>العدد</h6>
                                                                <div class="qty-box">
                                                                    <div class="input-group">
                                                                        <button class="qty-minus"></button>
                                                                        <input class="qty-adj form-control" type="number"
                                                                            value="1">
                                                                        <button class="qty-plus"></button>
                                                                    </div>
                                                                </div>
                                                                <h6>المقاس</h6>
                                                                <div class="size-box">
                                                                    <ul>
                                                                        <li><a href="javascript:void(0)">50*50</a></li>
                                                                        <li><a href="javascript:void(0)">100*100</a></li>
                                                                        <li><a href="javascript:void(0)">150*150</a></li>

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="pro-add">
                                                                <a href="javascript:void(0)" class="tooltip-top"
                                                                    data-tippy-content="Move to wish list">
                                                                    <i data-feather="heart"></i>
                                                                </a>
                                                                <a href="javascript:void(0)" class="tooltip-top"
                                                                    data-tippy-content="Remove roduct">
                                                                    <i data-feather="trash-2"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="media">
                                                            <img src="assets/images/layout-2/product/2.jpg"
                                                                class="img-fluid" alt="">
                                                            <div class="media-body">
                                                                <h3>مج</h3>
                                                                <h4>le 70<span>le100</span></h4>
                                                                <h6>العدد</h6>
                                                                <div class="qty-box">
                                                                    <div class="input-group">
                                                                        <button class="qty-minus"></button>
                                                                        <input class="qty-adj form-control" type="number"
                                                                            value="1">
                                                                        <button class="qty-plus"></button>
                                                                    </div>
                                                                </div>
                                                                <h6>المقاس</h6>
                                                                <div class="size-box">
                                                                    <ul>
                                                                        <li><a href="javascript:void(0)">50*50</a></li>
                                                                        <li><a href="javascript:void(0)">100*100</a></li>
                                                                        <li><a href="javascript:void(0)">150*150</a></li>

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="pro-add">
                                                                <a href="javascript:void(0)" class="tooltip-top"
                                                                    data-tippy-content="Move to wish list">
                                                                    <i data-feather="heart"></i>
                                                                </a>
                                                                <a href="javascript:void(0)" class="tooltip-top"
                                                                    data-tippy-content="Remove roduct">
                                                                    <i data-feather="trash-2"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="order-tracking-sidebar order-tracking-box">

                                            <ul class="cart_total">
                                                <li>
                                                    المجموع : <span>le 1050.00</span>
                                                </li>
                                                <li>
                                                    الخصم <span>le 80</span>
                                                </li>
                                                <li>
                                                    شحن <span>مجاني</span>
                                                </li>

                                                <li>
                                                    الضرائب <span>0.00</span>
                                                </li>
                                                <li>
                                                    <div class="total">
                                                        الاجمالي<span>le 1050.00</span>
                                                    </div>
                                                </li>
                                                <li class="pt-0">
                                                    <div class="buttons">
                                                        <a href="cart.html" class="btn btn-solid btn-sm btn-block">تأكيد
                                                            الاوردر</a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:void(0)" class="next action-button btn btn-solid btn-sm">التالي</a>
                        </fieldset>
                        <fieldset>
                            <div class="container p-0">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="order-address order-tracking-box">
                                            <h4 class="tracking-title">بيانات التواصل</h4>
                                            <div class="form-group">
                                                <input type="text" placeholder="enter your first name"
                                                    class="form-control">
                                                <input type="text" placeholder="enter your last name"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" placeholder="enter your address "
                                                    class="form-control">
                                                <input type="text" placeholder="enter your mobile number"
                                                    class="form-control">
                                            </div>
                                            <h4 class="tracking-title">تفاصيل العنوان</h4>
                                            <div class="form-group">
                                                <select class="form-control">
                                                    <option value="">المدينة</option>
                                                    <option value="1">القاهرة</option>
                                                    <option value="2">الجيزة</option>
                                                    <option value="3">الاسكندرية</option>
                                                </select>
                                                <select class="form-control">
                                                    <option value="">اختر المنطقة</option>
                                                    <option value="1">الهرم</option>
                                                    <option value="2">اكتوبر</option>
                                                    <option value="3">الشيخ زايد</option>
                                                </select>
                                            </div>

                                            <h4 class="tracking-title">حفظ العنوان</h4>
                                            <div class="form-group">
                                                <div>
                                                    <ul class="addresh-locat">
                                                        <li>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio"
                                                                    class="custom-control-input form-check-input"
                                                                    id="home-add" name="example">
                                                                <label class="custom-control-label form-check-label mb-0"
                                                                    for="home-add">المنزل</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio"
                                                                    class="custom-control-input form-check-input"
                                                                    id="office-add" name="example">
                                                                <label class="custom-control-label form-check-label mb-0"
                                                                    for="office-add">المكتب</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <div class="custom-control custom-checkbox  form-check">
                                                        <input type="checkbox"
                                                            class="custom-control-input form-check-input"
                                                            id="customCheck1" checked="">
                                                        <label class="custom-control-label form-check-label mb-0"
                                                            for="customCheck1">العنوان الرئيسي</label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-group mb-0">
                                                <a href="#" class="btn btn-solid btn-sm">إضافة عنوان</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:void(0)"
                                class="btn btn-solid btn-sm previous action-button-previous">السابق</a>
                            <a href="javascript:void(0)" class="btn btn-solid btn-sm next action-button">next</a>
                        </fieldset>
                        <fieldset>

                            <a href="javascript:void(0)"
                                class="btn btn-solid btn-sm previous action-button-previous">السابق</a>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--order tracking end-->
@endsection
