<table>

    <thead>
        <tr>
            <th>
                رقم الأوردر
            </th>
            <th>
                اسم العميل
            </th>
            <th>
                رقم الهاتف
            </th>
            <th>
                العربون
            </th> 
            <th>
                الأجمالي
            </th>
            <th>
                نسبة الربح
            </th>
            <th>
                محتوي الطلب
            </th>
            <th>
                بواسطة
            </th>
            <th>
                تاريخ
            </th>
        </tr>
    </thead>


    @php
        $sum = 0;
        $sum2 = 0;
    @endphp

    <tbody>

        @foreach($receipts as $receipt)
            @php
                $sum += $receipt->calc_total_for_client() ;
                $sum2 += ($receipt->commission + $receipt->extra_commission) ;
                $description = '';
                foreach($receipt->receiptsReceiptSocialProducts as $key => $product){
                    $description .= $product->title . " - [ (" . $product->price . "x" . $product->quantity . ") = " . $product->total_cost . "] ";
                    $description .= '<br> --------- <br>';
                }
            @endphp

            <tr>
                <td>{{ $receipt->order_num }}</td>
                <td>{{ $receipt->client_name }}</td>
                <td>{{ $receipt->phone_number }}</td>
                <td>{{ $receipt->deposit }}</td> 
                <td>{{ $receipt->calc_total_for_client() }}</td>
                <td>{{ $receipt->commission + $receipt->extra_commission }}</td>
                <td><?php echo nl2br($description ?? ''); ?></td>
                <td>{{ $receipt->staff ? $receipt->staff->email : '' }}</td>
                <td>{{ $receipt->created_at }}</td>
            </tr>
        @endforeach

        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td> 
            <td></td>
            <td>المجموع : {{ $sum }}</td>
            <td>المجموع : {{ $sum2 }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </tbody>
</table>
