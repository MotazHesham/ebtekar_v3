<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>فاتورة
        عرض السعر</title>
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
            font-weight: bolder;
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
    @foreach($receipts as $receipt)
        @php
            $site_settings = \App\Models\WebsiteSetting::find($receipt->website_setting_id);
        @endphp
        <div style="page-break-after: always;">
            <div style="background: #eceff4;padding: 1.5rem;">
                <table>
                    <tr>
                        <td> 
                            <img loading="lazy" src="{{ asset($site_settings->logo->getUrl()) }}" height="70"
                                style="display:inline-block;"> 
                        </td>
                    </tr>
                </table>
                <h2 style="text-align: center">عرض سعر</h2>
            </div>

            <div style="padding: 1.5rem;">

                <table style="padding: 1.5rem;float: right;position: relative;">
                    <img src="{{ asset($site_settings->logo->getUrl()) }}" alt=""
                        style="position: absolute;opacity:0.1;width:100%">
                    <tr>
                        <td class="text-right small" style="font-size: 1.2rem;">
                            <span>{{ $receipt->created_at }}</span>
                            <span class="gry-color strong" style="float:right">: التاريخ </span>
                        </td>
                        <td class="text-right small" style="font-size: 1.2rem;">
                            <span>{{ $receipt->client_name }}</span>
                            <span class="gry-color strong" style="float:right">: إلي السيد </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right small" style="font-size: 1.2rem;">
                            <span>{{ $receipt->supply_duration }}</span>
                            <span class="gry-color strong" style="float:right">: مدة التوريد </span>
                        </td>
                        <td class="text-right small" style="font-size: 1.2rem;">
                            <span>{{ $receipt->relate_duration }}</span>
                            <span class="gry-color strong" style="float:right">: مدة الأرتباط </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right small" style="font-size: 1.2rem;">
                            <span>{{ $receipt->place }}</span>
                            <span class="gry-color strong" style="float:right">: مكان التسليم </span>
                        </td>
                        <td class="text-right small" style="font-size: 1.2rem;">
                            <span>{{ $receipt->payment }}</span>
                            <span class="gry-color strong" style="float:right">: الدفع</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right small" style="font-size: 1.2rem;">
                            <span>{{ $receipt->phone_number }}</span>
                            <span class="gry-color strong" style="float:right">: تليفون </span>
                        </td>
                        <td class="text-right small" style="font-size: 1.2rem;">
                            <span>{{ $receipt->added_value ? 'شامل' : 'غير شامل' }}</span>
                            <span class="gry-color strong" style="float:right">: شامل / غير شامل %14</span>
                        </td>
                    </tr>
                </table>

                <table class="padding text-left small border-bottom">

                    <img src="{{ asset($site_settings->logo->getUrl()) }}" alt=""
                        style="position: absolute;opacity:0.1;top:180px;width:100%">
                    <thead>
                        <tr class="gry-color" style="background: #eceff4;">
                            <th width="35%">الصنف</th>
                            <th width="15%">الكمية</th>
                            <th width="10%">السعر</th>
                            <th width="15%" class="text-right">الأجمالي</th>
                        </tr>
                    </thead>
                    <tbody class="strong">
                        @foreach ($receipt->receiptPriceViewReceiptPriceViewProducts as $key => $product)
                            @if ($receipt->receiptPriceViewReceiptPriceViewProducts != null)
                                <tr class="">
                                    <td>{{ $product->description }}</td>
                                    <td class="gry-color">{{ $product->quantity }}</td>
                                    <td class="gry-color currency">
                                        {{ dashboard_currency($product->price) }}
                                    </td>
                                    <td class="text-right currency">
                                        {{ dashboard_currency($product->total_cost) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div style="padding:0 1.5rem;">
                <table style="width: 40%;margin-left:auto;" class="text-right sm-padding small strong">
                    <tbody>
                        @if($receipt->added_value == 1)
                            <tr>
                                <td class="currency">{{ dashboard_currency($receipt->total_cost) }}
                                </td>
                                <th width="35%" class="gry-color text-right">الأجمالي</th>
                            </tr>
                            <tr>

                                <td class="currency">{{ dashboard_currency($receipt->calc_added_value()) }}
                                </td>
                                <th width="35%" class="gry-color text-right">(قيمة %14)</th>
                            </tr>
                        @endif

                        <tr>
                            <td class="currency">{{ dashboard_currency($receipt->calc_total_cost()) }}
                            </td>
                            <th width="35%" class="gry-color text-right">المجموع</th>
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
