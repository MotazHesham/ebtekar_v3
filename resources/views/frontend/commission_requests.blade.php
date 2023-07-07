@extends('frontend.layout.app') 

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2> طلبات السحب </h2>
                            <ul>
                                <li><a href="index.html">الرئيسية</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)"> طلبات السحب </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->


    <!--section start-->
    <section class="cart-section order-history section-big-py-space"> 
        <div class="custom-container">
            @if(auth()->check() && auth()->user()->user_type == 'seller') 
                <div class="row mt-5">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header"> 
                                طلبات السحب
                            </div>
                            <div class="card-body">
                                <table class="table cart-table table-responsive-xs">
                                    <thead>
                                        <tr class="table-head">
                                            <th scope="col">تاريخ طلب السحب	</th>
                                            <th scope="col">طريقة الدفع او السداد</th>
                                            <th scope="col">رقم التحويل	</th> 
                                            <th scope="col">نسبة ربح الطلبات</th>
                                            <th scope="col">الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($commission_requests as $commission_request) 
                                            <tr>
                                                <td>
                                                    {{ $commission_request->created_at }}
                                                </td>
                                                <td>
                                                    {{ \App\Models\CommissionRequest::PAYMENT_METHOD_SELECT[$commission_request->payment_method] }}
                                                </td>
                                                <td>
                                                    {{ $commission_request->transfer_number }}
                                                </td> 
                                                
                                                <td>
                                                    {{ $commission_request->total_commission }}
                                                </td>
                                                <td>
                                                    @if($commission_request->status == 'requested')
                                                        <span class="badge bg-primary"> {{ \App\Models\CommissionRequest::STATUS_SELECT[$commission_request->status] }}</span>
                                                    @elseif($commission_request->status == 'delivered')
                                                        <span class="badge bg-success"> {{ \App\Models\CommissionRequest::STATUS_SELECT[$commission_request->status] }}</span>
                                                        <br>
                                                        {{$commission_request->done_time}}
                                                    @elseif($commission_request->status == 'pending')
                                                        <span class="badge bg-info"> {{ \App\Models\CommissionRequest::STATUS_SELECT[$commission_request->status] }}</span>
                                                    @endif
                                                </td>
                                            </tr> 
                                        @endforeach
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                    <div class="product-pagination">
                        <div class="theme-paggination-block">
                            <div class="container-fluid p-0">
                                <div class="row">
                                    <div class="col-xl-8 col-md-8 col-sm-12">
                                        <nav aria-label="Page navigation"> 
                                            {{ $commission_requests->appends(request()->input())->links('vendor.pagination.custom.products') }}
                                        </nav>
                                    </div>
                                    <div class="col-xl-4 col-md-4 col-sm-12">
                                        <div class="product-search-count-bottom">
                                            <span style="padding:5px"> عرض </span>

                                            @if ($commission_requests->firstItem())
                                                <b> {{ $commission_requests->firstItem() }} </b>

                                                <span style="padding:5px"> إلي </span>

                                                <b> {{ $commission_requests->lastItem() }} </b>

                                            @else
                                                {{ $commission_requests->count() }}
                                            @endif
                                            <span style="padding:5px"> من </span>

                                            <b> {{ $commission_requests->total() }} </b>

                                            <span style="padding:5px"> النتائج </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                
            @endif
        </div>
    </section>
    <!--section end-->
@endsection 