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
                        <td>{{ $order->order_num }}</td>
                        <td>{{ $order->client_name }}</td>  
                        <td>{{$order->phone_number}} - {{$order->phone_number_2}}</td>
                        <td>
                            {{ dashboard_currency($order->calc_total_for_client()) }}
                        </td> 
                        <td>{{dashboard_currency($order->commission)}}</td> 
                        <td>
                            {{ $order->delivery_status ? __('global.delivery_status.status.' . $order->delivery_status) : '' }}
                        </td>  
                        <td>{{ $order->created_at }}</td> 
                    </tr>
                @endforeach  
            @endif
        @endisset

    </tbody>
</table>