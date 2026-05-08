@extends('frontend.layout.app')

@section('content')
    <div class="breadcrumb-main">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>Marketer Dashboard</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="{{ route('frontend.marketer.dashboard') }}">Dashboard</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section-big-py-space b-g-light">
        <div class="container">
            <div class="row">
                @include('frontend.partials.dashboard_navbar')

                <div class="col-lg-9">
                    <div class="dashboard-right">
                        <div class="dashboard">
                            <div class="page-title">
                                <h2>{{ $marketer->name }} - Stats</h2>
                                <p>Referral Link: <a href="{{ url('/?ref=' . $marketer->code) }}">{{ url('/?ref=' . $marketer->code) }}</a></p>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3"><div class="p-3 bg-white border">Total Orders: {{ $totalOrders }}</div></div>
                                <div class="col-md-4 mb-3"><div class="p-3 bg-white border">Total Sales: {{ number_format($totalSales, 2) }}</div></div>
                                <div class="col-md-4 mb-3"><div class="p-3 bg-white border">Total Commission: {{ number_format($totalCommission, 2) }}</div></div>
                                <div class="col-md-4 mb-3"><div class="p-3 bg-white border">Paid: {{ number_format($paidCommission, 2) }}</div></div>
                                <div class="col-md-4 mb-3"><div class="p-3 bg-white border">Remaining: {{ number_format($remainingCommission, 2) }}</div></div>
                                <div class="col-md-4 mb-3"><div class="p-3 bg-white border">Customers: {{ $customersCount }}</div></div>
                                <div class="col-md-4 mb-3"><div class="p-3 bg-white border">Conversion: {{ $conversionRate }}%</div></div>
                                <div class="col-md-4 mb-3"><div class="p-3 bg-white border">Delivered: {{ $deliveredOrders }}</div></div>
                                <div class="col-md-4 mb-3"><div class="p-3 bg-white border">Cancelled/Returned: {{ $cancelledOrReturnedOrders }}</div></div>
                            </div>

                            <div class="mt-4">
                                <h4>Recent Orders</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Client</th>
                                                <th>Phone</th>
                                                <th>Total</th>
                                                <th>Shipping</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentOrders as $order)
                                                <tr>
                                                    <td>{{ $order->order_num }}</td>
                                                    <td>{{ $order->client_name }}</td>
                                                    <td>{{ $order->phone_number }}</td>
                                                    <td>{{ number_format((float) $order->total_cost, 2) }}</td>
                                                    <td>{{ number_format((float) $order->shipping_country_cost, 2) }}</td>
                                                    <td>{{ $order->delivery_status }}</td>
                                                    <td>{{ $order->created_at }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="7" class="text-center">No orders yet.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
