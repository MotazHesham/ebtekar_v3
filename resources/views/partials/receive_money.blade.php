<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>استلام نقدية</title>
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
    <style type="text/css" media="print">
        @page {
            size: landscape;
        }
    </style>
</head>

<body>
    @php
        $site_settings = get_site_setting();
    @endphp
    <div> 
        <div style="background: #eceff4;padding: 1.5rem;">
            <table>
                <tr>
                    <td>
                        <img loading="lazy" src="{{ asset($site_settings->logo->getUrl()) }}" height="100"
                            style="display:inline-block;">
                    </td>
                </tr>
            </table>
            <h2 style="text-align: center;color:rgb(87, 22, 22)">إيصال إستلام نقدية / شيكات</h2>
        </div>
        <table style="padding: 1.5rem;float: right;position: relative;">
            <img src="{{ asset($site_settings->logo) }}" alt=""
                style="position: absolute;opacity:0.1;top:100px;left:200px">
            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"> </td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px">
                    <span>{{ $receipt->created_at }}</span> <span class="gry-color strong"
                        style="float:right">: تحرير في </span></td>
            </tr>

            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"> </td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px">
                    <span>{{ $receipt->client_name }}</span> <span class="gry-color strong" style="float:right">: استلمت
                        من السيد</span></td>
            </tr>

            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"> </td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"> <span>جنيه</span> <span
                        class="gry-color strong" style="float:right">{{ $receipt->deposit }}: مبلغ وقدره</span></td>
            </tr>

            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"><span> </span> <span
                        class="gry-color strong" style="float:right">...........................................: مسحوب
                        علي بنك</span></td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"><span> </span> <span
                        class="gry-color strong" style="float:right">...........................................: نقدا /
                        بشيك رقم</span></td>
            </tr>

            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"> </td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"><span
                        class="gry-color strong">...........................................................................................:
                        وذلك قيمة</span></td>
            </tr>


        </table>

        <table style="padding: 1.5rem;float: right;position: relative;">

            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"><span> </span> <span
                        class="gry-color strong" style="float:right;text-align:center;color:rgb(87, 22, 22)">
                        المستلم</span></td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"><span> </span> <span
                        class="gry-color strong" style="float:right;text-align:center;color:rgb(87, 22, 22)">
                        الحسابات</span></td>
            </tr>
            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"><span> </span> <span
                        class="gry-color strong"
                        style="float:right">.............................................</span></td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"><span> </span> <span
                        class="gry-color strong"
                        style="float:right">.............................................</span></td>
            </tr>
        </table>

    </div>

    <script>
        window.print()
    </script>

</body>

</html>
