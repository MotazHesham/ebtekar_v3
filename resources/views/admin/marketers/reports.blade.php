@extends('layouts.admin')
@section('content')
    <div class="card mb-3">
        <div class="card-header">Marketers Report Filters</div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.marketers.reports') }}">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="marketer_id">Marketer</label>
                        <select name="marketer_id" id="marketer_id" class="form-control select2">
                            <option value="">All</option>
                            @foreach ($marketers as $marketer)
                                <option value="{{ $marketer->id }}" {{ request('marketer_id') == $marketer->id ? 'selected' : '' }}>
                                    {{ $marketer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="commission_status">Status</label>
                        <select name="commission_status" id="commission_status" class="form-control">
                            <option value="">All</option>
                            @foreach (['pending', 'approved', 'rejected', 'paid'] as $status)
                                <option value="{{ $status }}" {{ request('commission_status') === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="source">Source</label>
                        <select name="source" id="source" class="form-control">
                            <option value="">All</option>
                            @foreach (['link', 'promo', 'admin_manual'] as $src)
                                <option value="{{ $src }}" {{ request('source') === $src ? 'selected' : '' }}>
                                    {{ $src }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="from_date">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="to_date">To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                    </div>
                    <div class="form-group col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">Filter</button>
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <a href="{{ route('admin.marketers.reports') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
            <div class="mt-2">
                <a class="btn btn-success btn-sm"
                    href="{{ route('admin.marketers.reports.excel', request()->query()) }}">Export Excel</a>
                <a class="btn btn-danger btn-sm"
                    href="{{ route('admin.marketers.reports.pdf', request()->query()) }}" target="_blank">Export PDF</a>
                <a class="btn btn-info btn-sm"
                    href="{{ route('admin.marketers.payout-history', request()->only(['marketer_id', 'from_date', 'to_date'])) }}">Payout History</a>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-2"><div class="card"><div class="card-body"><strong>Orders</strong><br>{{ $totals['totalOrders'] }}</div></div></div>
        <div class="col-md-2"><div class="card"><div class="card-body"><strong>Sales Net</strong><br>{{ number_format($totals['totalSales'], 2) }}</div></div></div>
        <div class="col-md-2"><div class="card"><div class="card-body"><strong>Commission</strong><br>{{ number_format($totals['totalCommission'], 2) }}</div></div></div>
        <div class="col-md-2"><div class="card"><div class="card-body"><strong>Paid</strong><br>{{ number_format($totals['totalPaid'], 2) }}</div></div></div>
        <div class="col-md-2"><div class="card"><div class="card-body"><strong>Due</strong><br>{{ number_format($totals['totalDue'], 2) }}</div></div></div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Add Payout</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.marketers.payout') }}">
                @csrf
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="payout_marketer_id">Marketer</label>
                        <select name="marketer_id" id="payout_marketer_id" class="form-control select2" required>
                            <option value="">Select marketer</option>
                            @foreach ($marketers as $marketer)
                                <option value="{{ $marketer->id }}" {{ request('marketer_id') == $marketer->id ? 'selected' : '' }}>
                                    {{ $marketer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="amount">Amount</label>
                        <input type="number" step="0.01" min="0.01" class="form-control" name="amount" id="amount" required>
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-5">
                        <label for="notes">Notes</label>
                        <input type="text" class="form-control" name="notes" id="notes" placeholder="Bank transfer ref, cash note, etc.">
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Confirm Payout</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Attributed Orders</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Order</th>
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
                        @forelse($rows as $row)
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
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No data for current filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $rows->links() }}
        </div>
    </div>
@endsection
