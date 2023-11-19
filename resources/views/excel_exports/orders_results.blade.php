<table>

    <thead>
        <tr>
            <th>id</th>
            <th>REF</th>
            <th>DEST</th>
            <th>COD</th>
            <th>COD FEE</th>
            <th>COD TAX</th>
            <th>NET BALANCE</th>

            <th>SHIPPING</th>
            <th>REMAIN</th>
        </tr>
    </thead>
    <tbody>
        @php
            $cod_total = 0;
            $shipping_total = 0;
            $remain_total = 0;
        @endphp
        @foreach($rows['accepted'] as $row)
            @php
                $cod_total += $row[3];
                $shipping_total += $row[7];
                $remain_total += $row[8];
            @endphp
            <tr>
                <td>{{$row[0]}}</td>
                <td>{{$row[1]}}</td>
                <td>{{$row[2]}}</td>
                <td>{{$row[3]}}</td>
                <td>{{$row[4]}}</td>
                <td>{{$row[5]}}</td>
                <td>{{$row[6]}}</td>
                <td>{{$row[7]}}</td>
                <td>{{$row[8]}}</td>
            </tr>
        @endforeach
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>total => {{$cod_total}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td>total => {{$shipping_total}}</td>
            <td>total => {{$remain_total}}</td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td>REJECTED</td>
            <td></td>
            <td></td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td>id</td>
            <td>REF</td>
            <td>DEST</td>
            <td>COD</td>
            <th>COD FEE</th>
            <th>COD TAX</th>
            <td>NET BALANCE</td>
            <td style="color:red">REASON</td>
        </tr>
        @foreach($rows['rejected'] as $row)
            <tr>
                <td>{{$row[0]}}</td>
                <td>{{$row[1]}}</td>
                <td>{{$row[2]}}</td>
                <td>{{$row[3]}}</td>
                <td>{{$row[4]}}</td>
                <td>{{$row[5]}}</td>
                <td>{{$row[6]}}</td>
                <td>{{$row[7]}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
