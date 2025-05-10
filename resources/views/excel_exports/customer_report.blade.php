<table>
    <thead>
        <tr>
            <th>
                اسم العميل
            </th>
            <th>
                رقم الهاتف
            </th>
            <th>
                عدد الطلبات
            </th>
        </tr>
    </thead>

    <tbody>
        @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->client_name }}</td>
                <td>{{ $customer->phone_number }}</td>
                <td>{{ $customer->order_count }}</td>
            </tr>
        @endforeach
    </tbody>
</table> 