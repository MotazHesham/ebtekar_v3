<table>
    <thead>
        <tr>
            <th>Order #</th>
            <th>Marketer</th>
            <th>Client</th>
            <th>Source</th>
            <th>Status</th>
            <th>Commission Base</th>
            <th>Commission</th>
            <th>Order Date</th>
            <th>Approved At</th>
            <th>Paid At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                <td>{{ $row->order->order_num ?? $row->order_id }}</td>
                <td>{{ $row->marketer->name ?? '-' }}</td>
                <td>{{ $row->order->client_name ?? '-' }}</td>
                <td>{{ $row->source }}</td>
                <td>{{ $row->commission_status }}</td>
                <td>{{ (float) $row->commission_base }}</td>
                <td>{{ (float) $row->commission_amount }}</td>
                <td>{{ $row->order->created_at ?? '-' }}</td>
                <td>{{ $row->approved_at ?? '-' }}</td>
                <td>{{ $row->paid_at ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
