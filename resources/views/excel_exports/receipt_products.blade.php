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
                الكمية
            </th>
            <th>
                الأجمالي
            </th>
            <th>
                تاريخ
            </th>
        </tr>
    </thead>



    <tbody>

        @foreach ($rows as $row)
            @php
                $sum = 0;
                $qnt = 0;
            @endphp
            <tr>

            </tr>
            <tr>

            </tr>
            <tr>

            </tr>
            <tr>
                <th style="color:red">{{ $row->name }}</th>
            </tr>
            <tr>

            </tr>
            @foreach ($row->product_in_receitps as $receipt_social_product)
                @if($receipt_social_product->receipt_social && $receipt_social_product->receipt_social->done) {{-- if receipt delivered --}}

                    @php
                        $sum += $receipt_social_product->total;
                        $qnt += $receipt_social_product->quantity;
                    @endphp
                    <tr>
                        <td>{{ $receipt_social_product->receipt_social->order_num ?? 'not-found' }}</td>
                        <td>{{ $receipt_social_product->receipt_social->client_name ?? 'not-found' }}</td>
                        <td>{{ $receipt_social_product->receipt_social->phone ?? 'not-found' }}</td>
                        <td>{{ $receipt_social_product->quantity }}</td>
                        <td>{{ $receipt_social_product->total }}</td>
                        <td>{{ format_date(strtotime($receipt_social_product->created_at)) }}</td>
                    </tr>
                @endif
            @endforeach
            <tr></tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>الكمية : {{ $qnt }}</td>
                <td>المجموع : {{ $sum }}</td>
                <td></td>
            </tr>
        @endforeach


    </tbody>
</table>
