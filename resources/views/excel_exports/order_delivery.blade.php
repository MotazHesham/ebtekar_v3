<table>

    <thead>
        <tr>
            <th>
                ShipperRef
            </th>
            <th>
                Consignee
            </th>
            <th>
                ConsigneeName
            </th>
            <th>
                ConsigneeMob1
            </th>
            <th>
                ConsigneeTel1
            </th>
            <th>
                Destination
            </th>
            <th>
                ConsigneeAddress1
            </th>
            <th>
                ConsigneeAddress2
            </th>
            <th>
                CODAmt
            </th>
            <th>
                NoofPieces
            </th>
            <th>
                Weight
            </th>
            <th>
                GoodsDesc
            </th>
            <th>
                SpecialInstruct
            </th>
            <th>
                ServiceType
            </th>
        </tr>
    </thead>


    @php
        $sum = 0;
        $sum2 = 0;

    @endphp

    <tbody>

        @foreach($orders as $order)
            @php
                $description = '';
                foreach($order->orderDetails as $key => $order_detail){
                    $name = $order_detail->product->name ?? 'N/A';
                    $quantity = $order_detail->quantity ?? 'N/A';
                    $description .= $name . " - [" . $quantity . "]";
                    $description .= '<br> --------- <br>';
                }
            @endphp

            <tr>
                <td>{{$order->order_num}}</td>
                <td>{{$order->client_name}}</td>
                <td>{{$order->client_name}}</td>
                <td>{{$order->phone_number_2 }}</td>
                <td>{{$order->phone_number }}</td>
                <td>{{$order->shipping_country ? $order->shipping_country->code : ''}}</td>
                <td>{{$order->shipping_address}}</td>
                <td></td>
                <td>{{ $order->calc_total_for_client() }}</td>
                <td>1</td>
                <td>1</td>
                <td><?php echo nl2br($description ?? ''); ?></td>
                <td>قابل للكسر</td>
                <td>cod</td>
            </tr>
        @endforeach

    </tbody>
</table>
