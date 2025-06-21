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
                الوصف
            </th> 
            <th>
                العربون
            </th> 
            <th>
                الخصم
            </th> 
            <th>
                الأجمالي
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
    @endphp

    <tbody> 

        @foreach($receipts as $receipt) 
            @php
                $sum += $receipt->total_cost;
            @endphp
            <tr>
                <td>{{ $receipt->order_num }}</td>
                <td>{{ $receipt->client_name }}</td> 
                <td>{{ $receipt->phone_number }}</td> 
                <td>
                    @foreach($receipt->receiptsReceiptBranchProducts as $product)
                        <?php echo nl2br($product->description ?? '') ?>
                        ({{ $product->quantity }}x{{ $product->price }} = {{ $product->total_cost }})
                        <br>
                        <br>
                    @endforeach
                </td> 
                <td>{{ $receipt->deposit }}</td> 
                <td>{{ $receipt->discount }}%</td> 
                <td>{{ $receipt->total_cost }}</td> 
                <td>{{ $receipt->staff ? $receipt->staff->name : '' }}</td> 
                <td>{{ $receipt->created_at }}</td> 
            </tr>
        @endforeach 

        
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td> 
            <td></td> 
            <td></td> 
            <td>المجموع : {{ $sum }}</td>
            <td></td>
            <td></td> 
        </tr>

    </tbody>
</table>