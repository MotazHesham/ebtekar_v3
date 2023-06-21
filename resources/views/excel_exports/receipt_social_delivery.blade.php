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

        @foreach($receipts as $receipt)
            @php
                $description = '';
                foreach($receipt->receiptsReceiptSocialProducts as $key => $product){
                    $description .= $product->title . " - [" . $product->quantity . "]";
                    $description .= '<br> --------- <br>';
                }
            @endphp

            <tr>
                <td>{{$receipt->order_num}}</td>
                <td>{{$receipt->client_name}}</td>
                <td>{{$receipt->client_name}}</td>
                <td>{{$receipt->phone_number_2 }}</td>
                <td>{{$receipt->phone_number }}</td>
                <td>{{$receipt->shipping_country ? $receipt->shipping_country->code : ''}}</td>
                <td>{{$receipt->shipping_address}}</td>
                <td></td>
                <td>{{ $receipt->calc_total_for_client() }}</td>
                <td>1</td>
                <td>1</td>
                <td><?php echo nl2br($description ?? ''); ?></td>
                <td>قابل للكسر</td>
                <td>cod</td>
            </tr>
        @endforeach

    </tbody>
</table>
