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
                $sum += $receipt->total ;
            @endphp
            <tr>
                <td>{{ $receipt->order_num }}</td>
                <td>{{ $receipt->client_name }}</td> 
                <td>{{ $receipt->phone }}</td> 
                <td>{{ $receipt->total }}</td> 
                <td>{{ $receipt->Staff ? $receipt->Staff->email : '' }}</td> 
                <td>{{ format_date($receipt->date) }}</td> 
            </tr>
        @endforeach 

        
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td> 
            <td>المجموع : {{ $sum }}</td>
            <td></td>
            <td></td> 
        </tr>

    </tbody>
</table>