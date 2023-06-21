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
                أجمالي المطلوب دفعه	
            </th> 
            <th>
                نسبة الربح
            </th> 
            <th>
                حالة التسليم
            </th>  
            <th>
                تاريخ
            </th> 
        </tr>
    </thead>

    

    <tbody> 

        @isset($orders)
            @if(count($orders) > 0)
                @foreach($orders as $order)  
                    <tr>
                        <td>{{ $order->code }}</td>
                        <td>{{ $order->client_name }}</td>  
                        <td>{{$order->phone_number}} - {{$order->phone_number2}}</td>
                        <td>
                            {{ single_price($order->required_to_pay + $order->shipping_country_cost -$order->deposit_amount) }}
                        </td> 
                        <td>{{single_price($order->commission)}}</td> 
                        <td>
                            {{ ucfirst(str_replace('_', ' ', $order->orderDetails->first()->delivery_status)) }}
                        </td>  
                        <td>{{ format_date($order->date) }}</td> 
                    </tr>
                @endforeach  
            @endif
        @endisset

    </tbody>
</table>