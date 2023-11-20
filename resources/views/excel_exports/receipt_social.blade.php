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
                المحفطة
            </th>
            <th>
                تاريخ
            </th>
        </tr>
    </thead>


    @php
        $sum = 0;
        $sum2 = 0;
        $sum3= 0;
    @endphp

    <tbody>

        @foreach($receipts as $receipt)
            @php
                $sum += $receipt->calc_total_for_client() ;
                $sum2 += ($receipt->commission + $receipt->extra_commission) ;
                $sum3 += $receipt->deposit ;
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
                <td>{{ $receipt->staff ? $receipt->staff->name : '' }}</td>
                <td> 
                    {{ $receipt->deposit_type ? \App\Models\ReceiptSocial::DEPOSIT_TYPE_SELECT[$receipt->deposit_type] : '' }}
                    <br>
                    {{ $receipt->financial_account ? $receipt->financial_account->account : '' }}
                    
                </td>
                <td>{{ $receipt->created_at }}</td>
            </tr>
        @endforeach

        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td> 
            <td>المجموع : {{ $sum3 }}</td>
            <td>المجموع : {{ $sum }}</td>
            <td>المجموع : {{ $sum2 }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </tbody>
</table>
