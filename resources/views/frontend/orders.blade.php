@extends('frontend.layout.app') 

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>{{ trans('frontend.dashboard.profile') }}</h2>
                            <ul>
                                <li><a href="{{ route('frontend.dashboard') }}">  {{ trans('frontend.about.dashboard') }} </a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="javascript:void(0)"> {{ trans('frontend.orders.orders') }}  </a></li>
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
                @php
                    $calculate_commission = calculate_commission(auth()->user()->orders);
                @endphp
                <div class="row">
                    
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body pb-0">
                                <div class="text-value">{{ $calculate_commission['delivered'] }}</div>
                                <div>  {{ trans('frontend.orders.delivered') }}
                                </div>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body pb-0">
                                <div class="text-value">{{ $calculate_commission['requested'] }}</div>
                                <div> {{ trans('frontend.orders.requested') }}
                                </div>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body pb-0">
                                <div class="text-value">{{ $calculate_commission['available'] }}</div>
                                <div>     {{ trans('frontend.orders.available') }}
                                </div>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body pb-0">
                                <div class="text-value">{{ $calculate_commission['pending'] }}</div>
                                <div> {{ trans('frontend.orders.pending') }}
                                </div>
                                <br />
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row mt-5">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header"> 
                            <div style="display: flex;justify-content: space-between">
                                <div>الطلبات</div> 
                                @if(auth()->check() && auth()->user()->user_type == 'seller')
                                    <button type="button" class="btn btn-normal" data-bs-toggle="modal" data-bs-target="#request_commission">طلب سحب</button>
                                @endif
                                <!-- Modal -->
                                <div class="modal fade" id="request_commission" tabindex="-1" aria-labelledby="request_commissionLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="request_commissionLabel">{{ trans('frontend.orders.request_commission') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('frontend.orders.request_commission.store') }}" method="POST">
                                                    @csrf
                                                    
                                                    <div class="form-group">
                                                        <label class=" control-label" for="payment_method">{{ trans('frontend.orders.payment_method') }}
                                                        </label> 
                                                        <select class="form-control demo-select2" name="payment_method" required> 
                                                            @foreach(\App\Models\CommissionRequest::PAYMENT_METHOD_SELECT as $key => $entry)
                                                                <option value="{{$key}}">{{$entry}}</option>  
                                                            @endforeach
                                                        </select>  
                                                    </div>
                                                    <div class="form-group">
                                                        <label class=" control-label" for="transfer_number"> {{ trans('frontend.orders.transfer_number') }}
                                                        </label> 
                                                        <input type="text" class="form-control" name="transfer_number" required>  
                                                    </div>
                                                    <div class="form-group">
                                                        <label class=" control-label" for="payment_method">  {{ trans('frontend.orders.deliver_orders') }} 
                                                        </label> 
                                                        <div style="padding-bottom: 4px">
                                                            <span class="btn btn-normal btn-sm select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                                            <span class="btn btn-normal btn-sm deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                                        </div>
                                                        <select name="orders[]" id="" class="form-control select2" multiple  required>
                                                            @foreach(auth()->user()->orders->where('delivery_status','delivered')->whereNotIn('commission_status',['delivered','requested']) as $order) 
                                                                <option value="{{$order->id}}">{{$order->order_num}}</option>
                                                            @endforeach
                                                        </select>  
                                                    </div>
                                                    <hr>
                                                    <button type="submit" class="btn btn-normal" >{{trans('frontend.orders.send_request')}}</button> 
                                                </form>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table cart-table table-responsive-xs">
                                <thead>
                                    <tr class="table-head">
                                        <th scope="col">{{ trans('frontend.orders.product') }}</th>
                                        <th scope="col">{{ trans('frontend.orders.description') }}</th>
                                        <th scope="col">{{ trans('frontend.orders.price') }}</th>
                                        @if(auth()->check() && auth()->user()->user_type == 'seller')
                                            <th scope="col">   {{ trans('frontend.orders.commission') }}</th>
                                        @endif
                                        <th scope="col">{{ trans('frontend.orders.details') }}</th>
                                        <th scope="col">{{ trans('frontend.orders.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderDetails as $orderDetail)
                                        @if($orderDetail->product)
                                            @php
                                                $image = '';
                                                $product = $orderDetail->product;
                                                $image = isset($product->photos[0]) ? $product->photos[0]->getUrl('preview2') : ''; 
                                            @endphp
                                            <tr>
                                                <td>
                                                    <a href="{{ route('frontend.product',$product->slug)}}"><img src="{{ $image }}"
                                                            alt="product" class="img-fluid  "></a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('frontend.product',$product->slug)}}"> {{ trans('frontend.orders.order_num') }} <span class="dark-data">{{$orderDetail->order->order_num ?? ''}}</span>
                                                        <br>{{$product->name ?? ''}}</a>
                                                </td>
                                                <td>
                                                    <b>{{ $orderDetail->total_cost($orderDetail->order->exchange_rate) }} {{ $orderDetail->order->symbol }}</b>
                                                </td> 
                                                @if(auth()->check() && auth()->user()->user_type == 'seller')
                                                    <td>
                                                        <b>{{ $orderDetail->calc_commission($orderDetail->order->exchange_rate) }} {{ $orderDetail->order->symbol }}</b>
                                                    </td>
                                                @endif
                                                <td>
                                                    <span>{{ trans('frontend.orders.quantity')}}: {{ $orderDetail->quantity }}</span>
                                                    <br>
                                                    <span> {{ $orderDetail->variation }} </span>
                                                </td>
                                                <td>
                                                    <div class="responsive-data">
                                                        <b>{{ $orderDetail->total_cost($orderDetail->order->exchange_rate) }} {{ $orderDetail->order->symbol }}</b>
                                                        <br>
                                                        <span> {{ $orderDetail->variation }} </span> | {{ trans('frontend.orders.quantity')}}: {{ $orderDetail->quantity }}</span>
                                                    </div>
                                                    <span class="dark-data">{{ $orderDetail->order->delivery_status ? trans('global.delivery_status.status.' . $orderDetail->order->delivery_status) : '' }}</span>
                                                        @if($orderDetail->order->delivery_status == 'delivered') ({{$orderDetail->order->done_time}}) @endif
                                                </td>
                                            </tr>
                                        @endif
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
                                        {{ $orderDetails->appends(request()->input())->links('vendor.pagination.custom.products') }}
                                    </nav>
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-12">
                                    <div class="product-search-count-bottom">
                                        <span style="padding:5px"> عرض </span>

                                        @if ($orderDetails->firstItem())
                                            <b> {{ $orderDetails->firstItem() }} </b>

                                            <span style="padding:5px"> إلي </span>

                                            <b> {{ $orderDetails->lastItem() }} </b>

                                        @else
                                            {{ $orderDetails->count() }}
                                        @endif
                                        <span style="padding:5px"> من </span>

                                        <b> {{ $orderDetails->total() }} </b>

                                        <span style="padding:5px"> النتائج </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </section>
    <!--section end-->
@endsection 