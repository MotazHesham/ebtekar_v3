<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>فاتورة سوشيال</title>

    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/bootstrap.min.css') }}">
    <script src="{{ asset('dashboard_offline/js/jquery.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/bootstrap.min.js') }}"></script>

	<style media="all">
		@font-face {
            font-family: 'DINNextLTArabic-Medium';
            src: url("{{ asset('css/DINNextLTArabic-Medium.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
		@page {
			size: auto;   /* auto is the initial value */
			margin: 0;  /* this affects the margin in the printer settings */
		}
        *{
            margin: 0;
            padding: 0;
            line-height: 1.3;
            color: #333542;
        }
        body{
            font-family:monospace;
            font-weight: bolder
        }
        .table-bordered{
            border:2px solid black
        }
    </style>
</head>

<body> 
    @foreach($receipts as $receipt)
        @php
            $site_settings = \App\Models\WebsiteSetting::find($receipt->website_setting_id);
        @endphp
        <div style="page-break-after: always;">
            <div style="padding: 1.5rem;postition:relative">
                <table style="position: absolute;top:80px;left:150px" class="text-center">
                    <tr>
                        <td class="gry-color small">{{ $site_settings->address }}</td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        <td class="gry-color small">Email: {{ $site_settings->email }}</td>
                    </tr>
                    <tr>
                        <td class="gry-color small" >Phone: {{ $site_settings->phone_number }}</td>
                    </tr>
                    <tr> 
                        <td class="gry-color" >{{ $receipt->created_at }}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td> 
                            <img src="{{ asset($site_settings->logo->getUrl()) }}" height="130" style="display:inline-block;">
                        </td>
                    </tr>
                </table>
                <div style="position: absolute;right:120px;top:65px;font-size:40px">

                    {!! QrCode::size(75)->generate($receipt->order_num) !!}
                    <div class="text-center" style="line-height: 0;">
                        @foreach($receipt->socials as $social)
                            <img src="{{asset($social->photo)}}" height="20" width="20" alt="">
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="text-center" style="margin-bottom: 10px;color:red !important">
                {{$receipt->order_num}}
                <span style="color:red;">:رقم الأوردر</span> 
            </div>
            <div style="position:relative"> 
                <table class="table table-bordered text-center" style="width:380px;position:absolute;right:10px;top:0" id="price-{{$receipt->id}}">
                    <thead>
                        <td>عدد القطع</td>
                        <td>المطلوب دفعه</td>
                        <td>العربون</td>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $receipt->receiptsReceiptSocialProducts()->sum('quantity')}}</td>
                            <td>
                                <span style="color:red !important">
                                    {{ dashboard_currency($receipt->calc_total_for_client()) }}  
                                </span>
                            </td>
                            <td>{{ dashboard_currency($receipt->deposit) }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered text-center" style="width:380px;position:absolute;left:10px;top:0" id="description-{{$receipt->id}}">
                    <thead>
                        <td>تفاصيل الأوردر</td>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @if ($receipt->receiptsReceiptSocialProducts != null)
                                    @foreach ($receipt->receiptsReceiptSocialProducts as $key => $product)
                                        <span style="color: white;background: black;padding: 5px;border-radius: 5px;">{{$product->title}} -</span>
                                        <span style="color: white;background: black;padding: 5px;border-radius: 5px;">[{{$product->quantity}}X] </span>
                                        <br>
                                        <span> @php echo $product->description; @endphp </span>
                                        <hr>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered text-center" style="width:380px;position:absolute;top:100px;right:10px" id="client_info-{{$receipt->id}}">
                    <tbody>
                        <tr>
                            <td>{{$receipt->client_name}}</td>
                            <td>اسم العميل</td>
                        </tr>
                        <tr>
                            <td>{{$receipt->phone_number}} @if($receipt->phone_number_2) - {{$receipt->phone_number_2}} @endif</td>
                            <td>رقم التليفون</td>
                        </tr>
                        <tr>
                            <td>{{$receipt->shipping_country->name ?? ''}} </td>
                            <td>المنطقة</td>
                        </tr>
                        <tr>
                            <td>{{$receipt->shipping_address}}</td>

                            <td>مكان التوصيل</td>
                        </tr>
                        <tr>
                            <td>
                                {{ $receipt->note }}
                            </td>
                            <td>ملاحطات</td>
                        </tr>
                    </tbody>
                </table>

            </div>


            <div id="second_part-{{$receipt->id}}" style="position: relative; width:90%;left:40px">
                <hr style="border-top: 2px dashed red">

                <div class="text-center" style="margin-bottom: 10px;color:red !important">
                    {{$receipt->order_num}}
                    <span style="color:red;">:رقم الأوردر</span>
                </div>
                <table class="table table-bordered text-center">
                    <tbody>
                        <tr>
                            <td>
                                {{ $receipt->created_at }}
                            </td>
                            <td colspan="2">
                                تحرير في
                            </td>
                            <td>
                                {{$receipt->staff ? $receipt->staff->name : ''}}
                            </td>
                            <td colspan="2">
                                الموظف
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">{{$receipt->client_name}}</td>
                            <td colspan="2">اسم العميل</td>
                        </tr>
                        <tr>
                            <td>
                                {{ $receipt->date_of_receiving_order }}
                            </td>
                            <td colspan="2">
                                تاريخ استلام
                            </td>
                            <td>
                                {{$receipt->phone_number}} @if($receipt->phone_number_2) - {{$receipt->phone_number_2}} @endif
                            </td>
                            <td colspan="2">
                                رقم التليفون
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                {{$receipt->shipping_country_name}}
                            </td>
                            <td colspan="4">المنطقة</td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                {{$receipt->shipping_address}}
                            </td>
                            <td colspan="2">مكان التوصيل</td>
                        </tr>
                        <tr>
                            <td>
                                {{ dashboard_currency($receipt->shipping_country_cost) }}  
                            </td>
                            <td colspan="2">
                                مصاريف الشحن
                            </td>
                            <td>
                                {{ dashboard_currency($receipt->calc_total_cost()) }}  
                            </td>
                            <td colspan="2">
                                حساب الأوردر
                            </td>
                        </tr>
                        </tr>
                        <tr>
                            <td>
                                {{ dashboard_currency($receipt->calc_total_for_delivery()) }}
                            </td>
                            <td>
                                حساب المندوب
                            </td>
                            <td>
                                <span style="color:red !important">
                                    {{ dashboard_currency($receipt->calc_total_for_client()) }}
                                </span>
                            </td>
                            <td>
                                المطلوب دفعه
                            </td>
                            <td>
                                {{ dashboard_currency($receipt->deposit) }}
                            </td>
                            <td>
                                العربون
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table text-center table-borderless" style="width: 270px;float:right">
                    <tr>
                        <td></td>
                        <td>
                        {!! QrCode::size(75)->generate($receipt->order_num) !!}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>

                <table class="table table-bordered text-center" style="width: 400px;float:left">
                    <tbody>
                        <tr>
                            <td>{{ $receipt->designer->name ?? '' }}</td>
                            <td>ديزاينر</td>
                        </tr>
                        <tr>
                            <td>{{ $receipt->manufacturer->name ?? '' }}</td>
                            <td>تصنيع</td>
                        </tr>
                        <tr>
                            <td>{{ $receipt->preparer->name ?? '' }}</td>
                            <td>تجهيز</td>
                        </tr>
                        <tr>
                            <td>{{ $receipt->shipmenter->name ?? '' }}</td>
                            <td>الأرسال للشحن</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
	<script>
        $(document).ready(function() {
            window.print()
        });
        
        @foreach ($receipts as $key => $receipt)
            var price_and_client_info = $('#price-{{$receipt->id}}').height() + $('#client_info-{{$receipt->id}}').height() + 50;
            var description = $('#description-{{$receipt->id}}').height() + 50;
            if (price_and_client_info > description) {
                var first_part = price_and_client_info;
            } else {
                var first_part = description;
            }
            $('#second_part-{{$receipt->id}}').css('top', first_part + 'px');
        @endforeach
	</script> 
</body> 
</html>
