<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Marketers Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        .totals { margin-bottom: 8px; }
        .totals span { margin-right: 12px; }
    </style>
</head>
<body>
    <h3>Marketers Report</h3>
    <div class="totals">
        <span>Orders: {{ $totals['totalOrders'] }}</span>
        <span>Sales Net: {{ number_format($totals['totalSales'], 2) }}</span>
        <span>Commission: {{ number_format($totals['totalCommission'], 2) }}</span>
        <span>Paid: {{ number_format($totals['totalPaid'], 2) }}</span>
        <span>Due: {{ number_format($totals['totalDue'], 2) }}</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Order</th>
                <th>Marketer</th>
                <th>Client</th>
                <th>Source</th>
                <th>Status</th>
                <th>Base</th>
                <th>Commission</th>
                <th>Order Date</th>
                <th>Approved</th>
                <th>Paid</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row->order->order_num ?? $row->order_id }}</td>
                    <td>{{ $row->marketer->name ?? '-' }}</td>
                    <td>{{ $row->order->client_name ?? '-' }}</td>
                    <td>{{ $row->source }}</td>
                    <td>{{ $row->commission_status }}</td>
                    <td>{{ number_format((float) $row->commission_base, 2) }}</td>
                    <td>{{ number_format((float) $row->commission_amount, 2) }}</td>
                    <td>{{ $row->order->created_at ?? '-' }}</td>
                    <td>{{ $row->approved_at ?? '-' }}</td>
                    <td>{{ $row->paid_at ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        window.print();
    </script>
</body>
</html>
