
<div style="display: flex;justify-content:center">
    <div style="text-align: center;
                width: 100%;
                align-self: center;
                align-content: center;
                align-items: center;
                box-shadow: 1px 2px 9px grey;
                padding: 30px;
                background: #8080800a;
                border-radius: 8px;">

            <div  style="text-align: start; ">
                <h1 style="float: left;display:inline">
                    <img loading="lazy" src="{{ asset($site_settings->logo->getUrl()) }}" height="40"
                        style="display:inline-block;">
                </h1>
            </div>
                
            <div style="float: right;display:inline" >
                
                <small >
                    {{ $site_settings->address }} <br>
                    {{ $site_settings->email }} <br>
                    {{ $site_settings->phone_number }}
                </small>
            </div>
            <div style="clear: both"></div>
        <hr>

        <div> 
            <h1 class="h3">{{__('Thank You for Your Order!')}}</h1>
            <h2 class="h5">{{__('Order Code:')}} {{ $order->order_num }}</h2> 
            <small>{{ __('You can track your order here') }} <a href="{{route('frontend.orders.track',$order->id)}}">Track Order</a></small>
        </div>
        <br> <br>
        <h3 style="float:left;margin-left:5%">{{__('Order Summary')}}</h3> 
        <div style="clear: both"></div>
        <hr style="float: left;margin-left:5%" width="150">
        <br><br>
        <div >
        

            <table style="width: 50%;text-align:center; ">
                <tr>
                    <th>{{__('Order date')}}</th>
                    <th>{{ $order->created_at }}</th>
                </tr>
                <tr>
                    <th>{{__('Payment method')}}</th>
                    <th>{{ \App\Models\Order::PAYMENT_TYPE_SELECT[$order->payment_type] }}</th>
                </tr>
                
                <tr>
                    <th>{{__('Phone Number')}}</th>
                    <th>{{ $order->phone_number }} , {{$order->phone_number_2}}</th>
                </tr>
                <tr>
                    <th>{{__('Order status')}}</th>
                    <th>{{ $order->delivery_status ? __('global.delivery_status.status.' . $order->delivery_status) : '' }}</th>
                </tr>
                <tr>
                    <th>{{__('Shipping address')}}</th>
                    <th>{{ $order->shipping_country->name }} , {{$order->shipping_address }}</th>
                </tr>
            </table> 

        </div>  
        <br><br><br>
        <div>
            <h3>{{__('Order Details')}}</h3>
            <hr width="50">
            <div>
                <table style="width: 100%;text-align:center"> 
                    <thead>
                        <tr>
                            <th style="padding: 20px">#</th>
                            <th>{{__('Product')}}</th>
                            <th>{{__('Variation')}}</th>
                            <th>{{__('Quantity')}}</th>
                            <th>{{__('Price')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderDetails as $key => $orderDetail)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td> 
                                    <a href="{{ route('frontend.product', $orderDetail->product->slug) }}" target="_blank">
                                        {{ $orderDetail->product->name }}
                                    </a>
                                    <br>
                                    @foreach($orderDetail->product->photos as $media)
                                        <img src="{{ $media->getUrl('thumb')}}" alt="">
                                    @endforeach
                                </td>
                                <td>
                                    {{ $orderDetail->variation }}
                                </td>
                                <td>
                                    {{ $orderDetail->quantity }}
                                </td>
                                <td>{{ $orderDetail->calc_price($order->exchange_rate) }} {{ $order->symbol }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> 
            <br><br><br>
            <div>
                <table style="width: 50%;text-align:end;float: right;">
                    <tbody>
                        <tr>
                            <td>
                                <span>{{ exchange_rate($order->total_cost,$order->exchange_rate) }} {{ $order->symbol }}</span>
                            </td>
                            <th>{{__('Subtotal')}}</th>
                        </tr>
                        <tr>
                            <td>
                                <span class="text-italic">{{ exchange_rate($order->shipping_country_cost,$order->exchange_rate) }} {{ $order->symbol }}</span>
                            </td>
                            <th>{{__('Shipping')}}</th>
                        </tr> 
                        <tr>
                            <td>
                                <span>-{{ exchange_rate($order->deposit_amount,$order->exchange_rate) }} {{ $order->symbol }}</span>
                            </td>
                            <th>{{__('Deposit')}}</th>
                        </tr>
                        <tr>
                            <td>
                                = {{ exchange_rate($order->calc_total_for_client(),$order->exchange_rate) }} {{ $order->symbol }}
                                @if($order->discount_code != null)
                                    <br>
                                    <span class="badge badge-purple">
                                        كود الخصم {{ $order->discount_code }}
                                        /
                                        {{ exchange_rate($order->calc_discount(),$order->exchange_rate) }} {{ $order->symbol }}
                                    </span>
                                @endif
                            </td>
                            <th><span>{{__('Total')}}</span></th>
                        </tr>
                    </tbody>
                </table>
            </div> 
        </div>
    </div>  
</div>
