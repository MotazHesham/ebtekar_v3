<table>

    <thead>
        
        @if($type == 'fedex')
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
        @else 
            <tr>
                <th>
                    Reference
                </th>
                <th>
                    IdNumber
                </th>
                <th>
                    Name
                </th>
                <th>
                    City
                </th>
                <th>
                    Country
                </th>
                <th>
                    Mobile
                </th>
                <th>
                    Telephone
                </th>
                <th>
                    Address1
                </th>
                <th>
                    Address2
                </th>
                <th>
                    Weight
                </th>
                <th>
                    shipType
                </th>
                <th>
                    codAmt
                </th>
                <th>
                    itemDesc
                </th>
                <th>
                    custVal
                </th>
                <th>
                    custCurr
                </th>
                <th>
                    gpsPoints
                </th>
                <th>
                    Boxes
                </th>
            </tr>
        @endif
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

            @if($type == 'fedex')
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
            @else 
                <tr>
                    <td>{{$receipt->order_num}}</td>
                    <td>{{$receipt->id}}</td>
                    <td>{{$receipt->client_name}}</td>
                    <td></td>
                    <td>{{$receipt->shipping_country ? $receipt->shipping_country->code : ''}}</td>
                    <td>{{$receipt->phone_number }}</td>
                    <td>{{$receipt->phone_number_2 }}</td>
                    <td></td>
                    <td>{{$receipt->shipping_address}}</td>
                    <td>1</td>
                    <td>DLV</td>
                    <td>{{ $receipt->calc_total_for_client() }}</td>
                    <td><?php echo nl2br($description ?? ''); ?></td>
                    <td>1</td>
                    <td>EGP</td> 
                    <td>0</td>
                    <td>1</td>
                </tr>
            @endif
        @endforeach

    </tbody>
</table>
