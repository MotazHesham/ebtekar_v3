<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ebtekar</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style media="all">
        @font-face {
            font-family: 'DINNextLTArabic-Medium';
            src: url("{{ asset('css/DINNextLTArabic-Medium.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        @page {
            size: auto;
            /* auto is the initial value */
            margin: 0;
            /* this affects the margin in the printer settings */
        }

        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            color: #333542;
        }

        body {
            font-size: .875rem;
            font-family: 'DINNextLTArabic-Medium';
        }

        .gry-color *,
        .gry-color {
            color: #878f9c;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .5rem .7rem;
        }

        table.padding td {
            padding: .7rem;
        }

        table.sm-padding td {
            padding: .2rem .7rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .small {
            font-size: .85rem;
        }

        .currency {}
    </style>
</head>

<body>
    @foreach($orders as $order)
        @php
            $site_settings = \App\Models\WebsiteSetting::find($order->website_setting_id);
        @endphp
        <div style="page-break-after: always;">
            <div style="background: #eceff4;padding: 1.5rem;">
                <table>
                    <tr>
                        <td>
                            <img loading="lazy" src="{{ asset($site_settings->logo->getUrl()) }}" height="40"
                                style="display:inline-block;">
                        </td>
                        <td style="font-size: 2.5rem;" class="text-right strong"> 
                            @php 
                                $bar_code = 'o-' . $order->id; 
                                echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($bar_code, config('app.barcode_type')) . '" alt="barcode"   />';
                            @endphp
                            {!! QrCode::size(100)->generate($order->order_num) !!}
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="font-size: 1.2rem;" class="strong">{{ $site_settings->site_name }}</td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        <td class="gry-color small">{{ $site_settings->address }}</td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        <td class="gry-color small">Email: {{ $site_settings->email }}</td>
                        <td class="text-right small"><span class="gry-color small">Order Code:</span> <span
                                class="strong">{{ $order->order_num }}</span></td>
                    </tr>
                    <tr>
                        <td class="gry-color small">Phone: {{ $site_settings->phone_number }}</td>
                        <td class="text-right small"><span class="gry-color small">Order Date:</span> <span
                                class=" strong">{{ $order->created_at }}</span></td>
                    </tr>
                </table>

            </div>

            <div style="padding: 1.5rem;padding-bottom: 0">
                @php
                    $user_type = $order->user ? $order->user->user_type : 'customer';
                @endphp
                <table>
                    @if ($user_type == 'seller')
                        <strong class="text-main">{{ __('Seller info') }}</strong><br>
                        {{ __('Email') }} :<strong class="text-main">{{ $order->user ? $order->user->email : '' }}</strong><br>
                        {{ __('Social Name') }} :<strong class="text-main">{{ $order->user ? $order->user->social_name : '' }}</strong><br>
                    @endif
                    <br><strong class="text-main">{{ __('Client info') }} :</strong><br>
                    <span style="float: left">{{ __('Name') }}</span>:<strong
                        class="text-main">{{ $order->client_name }}</strong><br>
                    @if ($user_type == 'customer')
                        <span style="float: left">{{ __('Email') }}</span>:<strong
                            class="text-main">{{ $order->user ? $order->user->email : '' }}</strong><br>
                    @endif
                    <span style="float: left">{{ __('Phone Number') }}</span>:<strong
                        class="text-main"><span>{{ $order->phone_number }} ,
                            {{ $order->phone_number_2 }}</span></strong><br>
                    <span style="float: left">{{ __('Address') }}</span>:<strong
                        class="text-main">{{ $order->shipping_country->name ?? '' }} ,
                        {{ $order->shipping_address }}</strong><br>
                    <span style="float: left">الدفع</span>:<strong
                        class="text-main">{{ $order->payment_type ? \App\Models\Order::PAYMENT_TYPE_SELECT[$order->payment_type] : '' }}</strong><br>
                    <span style="float: left">حالة الدفع</span>:<strong
                        class="text-main">{{ $order->payment_status ? __('global.payment_status.status.' . $order->payment_status) : '' }}</strong><br>
                </table>
            </div>

            <div style="padding: 1.5rem;">
                <table class="padding text-left small border-bottom">

                    <img src="{{ asset($site_settings->logo) }}" alt=""
                        style="position: absolute;opacity:0.25;top:180px;">
                    <thead>
                        <tr class="gry-color" style="background: #eceff4;">
                            <th width="35%">Product Name</th>
                            <th width="35%">Photo</th>
                            <th width="35%">Description</th>
                            <th width="10%">Qty</th>
                            <th width="15%">Unit Price</th>
                            <th width="15%" class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="strong">
                        @foreach ($order->orderDetails as $key => $orderDetail) 
                            <tr class="">
                                <td>{{ $orderDetail->product ? $orderDetail->product->name : '' }} - {{ $orderDetail->variation }}</td>
                                <td> 
                                    <img height="50" src={{ asset('#') }} />  
                                </td>
                                <td><?php echo $orderDetail->description; ?></td>
                                <td class="gry-color">{{ $orderDetail->quantity }}</td>
                                <td class="gry-color currency">{{ dashboard_currency($orderDetail->price) }}</td>
                                <td class="text-right currency">{{ dashboard_currency($orderDetail->total_cost) }}</td>
                            </tr> 
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:0 1.5rem;">
                <table style="width: 40%;margin-left:auto;" class="text-right sm-padding small strong">
                    <tbody>
                        <tr>
                            <th class="gry-color text-left">{{ __('cruds.order.extra.sub_total') }}</th>
                            <td class="currency">+ {{ dashboard_currency($order->calc_total_cost()) }}
                            </td>
                        </tr>
                        <tr>
                            <th class="gry-color text-left">{{ __('cruds.order.fields.deposit_amount') }}</th>
                            <td class="currency">- {{ dashboard_currency($order->deposit_amount) }}</td>
                        </tr>
                        <tr>
                            <th class="gry-color text-left">{{ __('cruds.order.fields.discount') }}</th>
                            <td class="currency">- {{ dashboard_currency($order->calc_discount()) }}</td>
                        </tr>
                        <tr>
                            <th class="gry-color text-left">{{ __('cruds.order.extra.shipping_country_cost') }}</th>
                            <td class="currency">+ {{ dashboard_currency($order->shipping_country_cost) }}</td>
                        </tr>
                        <tr>
                            <th class="text-left strong">{{ __('cruds.order.fields.total') }}</th>
                            <td class="currency">=
                                {{ dashboard_currency($order->calc_total_for_client()) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    @endforeach
    <script src="{{ asset('dashboard_offline/js/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            window.print()
        });
    </script>
</body>

</html>
