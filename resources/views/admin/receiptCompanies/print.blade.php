<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>فاتورة
        أبتكار</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
			font-size: .875rem;
			font-family: 'DINNextLTArabic-Medium';
		}
		.gry-color *,
		.gry-color{
			color:#878f9c;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .5rem .7rem;
		}
		table.padding td{
			padding: .7rem;
		}
		table.sm-padding td{
			padding: .2rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:left;
		}
		.text-right{
			text-align:right;
		}
		.small{
			font-size: .85rem;
		}
		.currency{

		}
	</style>
</head>
<body>
    @php
        $site_settings = get_site_setting();
    @endphp
    @foreach($receipts as $receipt)
        <div style="page-break-after: always;">
            <div style="background: #eceff4;padding: 1.5rem;">
                <table>
                    <tr>
                        <td>
                            <img loading="lazy" src="{{ asset($site_settings->logo->getUrl()) }}" height="40"
                                style="display:inline-block;">
                        </td>
                        <td style="font-size: 2.5rem;" class="text-right strong">{!! QrCode::size(100)->generate($receipt->order_num) !!}</td>
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
                    </tr>
                    <tr>
                        <td class="gry-color small" >Phone: {{ $site_settings->phone }}</td>
                    </tr>
                </table>

            </div>
            <table style="padding: 1.5rem;float: right;position: relative;">
                <img src="{{ asset($site_settings->logo->getUrl()) }}" alt="" style="position: absolute;opacity:0.25">
                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{ $receipt->created_at }}</span> <span class="gry-color strong" style="float:right">: تحرير في </span></td>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"><span >{{$receipt->staff ? $receipt->staff->name : ''}}</span> <span class="gry-color strong" style="float:right" >: الموظف</span></td>
                </tr>

                <tr >
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"><span >{{$receipt->order_num}}</span> <span class="gry-color strong" style="float:right" >: رقم الأوردر</span></td>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{$receipt->client_name}}</span> <span class="gry-color strong" style="float:right" >: اسم العميل</span></td>
                </tr>

                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{ dashboard_currency($receipt->total_cost) }}</span> <span class="gry-color strong" style="float:right"  >: حساب الأوردر</span></td>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{$receipt->phone_number}} @if($receipt->phone_number_2) - {{$receipt->phone_number_2}} @endif</span> <span class="gry-color strong" style="float:right" >: رقم التليفون</span></td>
                </tr>

                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{ dashboard_currency($receipt->shipping_country_cost) }}</span> <span class="gry-color strong" style="float:right" >: مصاريف الشحن</span></td>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span class="gry-color strong" style="float:right"  >: مكان التوصيل</span> <br> <div style="font-size: 19px;width:400px;float:right">
                        {{$receipt->shipping_country ? $receipt->shipping_country->name : ''}} ,
                        <br>{{$receipt->shipping_address}}</div>  </td>
                </tr>

                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{ dashboard_currency($receipt->deposit) }}</span> <span class="gry-color strong" style="float:right" >: العربون</span></td>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{ $receipt->date_of_receiving_order }} </span> <span class="gry-color strong" style="float:right">:  تاريح استلام الطلب من العميل</span></td>
                </tr>

                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span > {{ dashboard_currency($receipt->calc_total_for_client()) }} </span> <span class="gry-color strong" style="float:right" >: المطلوب دفعه</span></td>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{ $receipt->deliver_date }} </span> <span class="gry-color strong" style="float:right">:  ميعاد التوصيل</span></td>
                </tr>


            </table>

            <table style="padding: 1.5rem;float: right;position: relative;">
                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:25px;padding-right:10%;position: relative;" >
                        <span style="padding-right: 12px;"> تسليم</span>
                        <span class=" strong" style="padding-right: 40px;"><div style=" border: black 1px solid; display:inline;padding:0px 16px"></div></span>
                        <span style="padding-right: 12px;"> تأجيل ليوم</span>
                        <span style="padding-right: 12px;" class=" strong"><div style=" border: black 1px solid; display:inline;padding:0px 16px"></div></span>
                    </td>
                </tr>
                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span class="gry-color strong">: تفاصيل الأوردر</span><br> <span class=" strong" ><?php echo $receipt->description;?></span></td>
                </tr>
                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px;padding-right:10%;padding-top:15px;position: relative;" >
                        <span style="text-align: center;padding:65px">* خاص بالشركة *</span><br>
                        <div style="padding-top:20px">
                            <span style="padding-right: 12px;"> الخزينة</span>
                            <span class=" strong" style="padding-right: 40px;"><div style=" border: black 1px solid; display:inline;padding:0px 16px"></div></span>
                            <span style="padding-right: 12px;">التشغيل</span>
                            <span class=" strong" style="padding-right: 12px;"><div style=" border: black 1px solid; display:inline;padding:0px 16px"></div></span>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    @endforeach
	<script>
		window.print()
	</script>

</body>
</html>
