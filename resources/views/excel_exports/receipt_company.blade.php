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
                ميعاد التوصيل
            </th> 
            <th style="width: 20px">
                عنوان
            </th> 
            <th>
                حساب الأوردر
            </th> 
            <th>
                تكلفة الشحن
            </th> 
            <th>
                العربون
            </th> 
            <th>
                أجمالي المطلوب دفعه
            </th> 
            <th style="width: 50px">
                وصف
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
        $need_to_pay = 0;
        $deposit = 0;
        $shipping_country_cost = 0;
        $order_cost = 0;
    @endphp

    <tbody> 

        @foreach($receipts as $receipt) 
            @php
                $need_to_pay += $receipt->need_to_pay ;
                $deposit += $receipt->deposit ;
                $shipping_country_cost += $receipt->shipping_country_cost ;
                $order_cost += $receipt->order_cost ;

                $description = strip_tags($receipt->description); 
            @endphp
            <tr>
                <td>{{ $receipt->order_num }}</td>
                <td>{{ $receipt->client_name }}</td> 
                <td>{{ $receipt->phone }}</td> 
                <td>{{ format_date($receipt->deliver_date) }}</td> 
                <td>{{ $receipt->address }}</td> 
                <td>{{ $receipt->order_cost }}</td> 
                <td>{{ $receipt->shipping_country_cost }}</td> 
                <td>{{ $receipt->deposit }}</td> 
                <td>{{ $receipt->need_to_pay }}</td>  
                <td>{{str_replace('&nbsp;', ' ', $description)}}</td> 
                <td>{{ $receipt->Staff ? $receipt->Staff->email : '' }}</td> 
                <td>{{ format_date(strtotime($receipt->created_at)) }}</td> 
            </tr>
        @endforeach 

        
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td> 
            <td></td>
            <td></td> 
            <td>المجموع : {{ $order_cost }}</td>
            <td>المجموع : {{ $shipping_country_cost }}</td>
            <td>المجموع : {{ $deposit }}</td>
            <td>المجموع : {{ $need_to_pay }}</td>
            <td></td> 
            <td></td>
            <td></td> 
        </tr>

    </tbody>
</table>