@extends('layouts.admin')
@section('content')
    <style>
        .rf-hero {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #fff;
            border: 0;
            border-radius: 12px;
        }
        .rf-card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .08);
        }
        .rf-kpi {
            border-radius: 12px;
            border: 1px solid #eef2f7;
        }
        .rf-kpi .label {
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .rf-kpi .value {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.1;
        }
        .rf-table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #64748b;
            border-top: 0;
        }
        .rf-badge {
            font-size: 11px;
            padding: .35rem .55rem;
            border-radius: 999px;
            background: #e2e8f0;
            color: #334155;
        }
    </style>

    <div class="card rf-hero mb-3">
        <div class="card-body py-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h4 class="mb-1">Referral Visits Dashboard</h4>
                    <div class="text-white-50">Track referral traffic, quality, and conversion in one view.</div>
                </div>
                <div class="mt-2 mt-md-0">
                    <span class="rf-badge">Live Data</span>
                </div>
            </div>
            <hr class="bg-white-50 my-3">
            <form method="GET" action="{{ route('admin.marketers.referral-dashboard') }}">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="marketer_id" class="text-white-50">Marketer</label>
                        <select name="marketer_id" id="marketer_id" class="form-control select2">
                            <option value="">All</option>
                            @foreach ($marketers as $marketer)
                                <option value="{{ $marketer->id }}" {{ request('marketer_id') == $marketer->id ? 'selected' : '' }}>
                                    {{ $marketer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="source" class="text-white-50">UTM Source</label>
                        <input type="text" id="source" name="source" class="form-control"
                            value="{{ request('source') }}" placeholder="facebook, instagram, ...">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="from_date" class="text-white-50">From Date</label>
                        <input type="date" id="from_date" name="from_date" class="form-control"
                            value="{{ request('from_date') }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="to_date" class="text-white-50">To Date</label>
                        <input type="date" id="to_date" name="to_date" class="form-control"
                            value="{{ request('to_date') }}">
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-light mr-2">Filter</button>
                        <a class="btn btn-outline-light" href="{{ route('admin.marketers.referral-dashboard') }}">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card rf-kpi"><div class="card-body"><div class="label">Total Visits</div><div class="value">{{ number_format($totalVisits) }}</div></div></div>
        </div>
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card rf-kpi"><div class="card-body"><div class="label">Unique Visitors</div><div class="value">{{ number_format($uniqueVisitors) }}</div></div></div>
        </div>
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card rf-kpi"><div class="card-body"><div class="label">Unique Sessions</div><div class="value">{{ number_format($uniqueSessions) }}</div></div></div>
        </div>
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card rf-kpi"><div class="card-body"><div class="label">Attributed Orders</div><div class="value">{{ number_format($attributedOrders) }}</div></div></div>
        </div>
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card rf-kpi"><div class="card-body"><div class="label">Delivered Orders</div><div class="value">{{ number_format($deliveredOrders) }}</div></div></div>
        </div>
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card rf-kpi"><div class="card-body"><div class="label">Conversion</div><div class="value">{{ $conversionRate }}%</div></div></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card rf-card mb-3">
                <div class="card-header bg-white"><strong>Top Marketers by Visits</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-sm rf-table mb-0">
                        <thead>
                            <tr><th>Marketer</th><th>Code</th><th class="text-right">Visits</th></tr>
                        </thead>
                        <tbody>
                            @forelse($topMarketers as $row)
                                <tr>
                                    <td>{{ $row->marketer->name ?? '-' }}</td>
                                    <td><span class="rf-badge">{{ $row->marketer->code ?? '-' }}</span></td>
                                    <td class="text-right">{{ number_format($row->visits) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card rf-card mb-3">
                <div class="card-header bg-white"><strong>UTM Source Breakdown</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-sm rf-table mb-0">
                        <thead>
                            <tr><th>UTM Source</th><th class="text-right">Visits</th></tr>
                        </thead>
                        <tbody>
                            @forelse($sourceBreakdown as $row)
                                <tr>
                                    <td>{{ $row->utm_source ?: '(empty)' }}</td>
                                    <td class="text-right">{{ number_format($row->visits) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card rf-card mb-3">
                <div class="card-header bg-white"><strong>Device Breakdown</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-sm rf-table mb-0">
                        <thead><tr><th>Device</th><th class="text-right">Visits</th></tr></thead>
                        <tbody>
                            @forelse($deviceBreakdown as $row)
                                <tr><td>{{ $row->device ?: '(unknown)' }}</td><td class="text-right">{{ number_format($row->visits) }}</td></tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card rf-card mb-3">
                <div class="card-header bg-white"><strong>Browser Breakdown</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-sm rf-table mb-0">
                        <thead><tr><th>Browser</th><th class="text-right">Visits</th></tr></thead>
                        <tbody>
                            @forelse($browserBreakdown as $row)
                                <tr><td>{{ $row->browser ?: '(unknown)' }}</td><td class="text-right">{{ number_format($row->visits) }}</td></tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card rf-card">
        <div class="card-header bg-white"><strong>Daily Visits Trend</strong></div>
        <div class="card-body table-responsive">
            <table class="table table-hover table-sm rf-table mb-0">
                <thead>
                    <tr><th>Date</th><th class="text-right">Visits</th></tr>
                </thead>
                <tbody>
                    @forelse($dailyVisits as $row)
                        <tr>
                            <td>{{ $row->day }}</td>
                            <td class="text-right">{{ number_format($row->visits) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center text-muted">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
