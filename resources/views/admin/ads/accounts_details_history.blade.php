@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('Ad History') }} — {{ $accountDetail->name }}
        <div class="float-right">
            <a class="btn btn-default btn-sm" href="{{ route('admin.ads.accounts.index', ['account_id' => $selectedAccount->id]) }}"><i class="fa fa-arrow-left"></i> {{ trans('Back') }}</a>
            <a class="btn btn-success btn-sm" href="{{ route('admin.ads.accounts.details.history.create', [$selectedAccount->id, $accountDetail->id]) }}"><i class="fa fa-plus"></i> {{ trans('Add New History') }}</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body py-3">
                        <div class="text-muted small text-uppercase">{{ trans('Total Spent') }}</div>
                        <div class="h5 font-weight-bold text-warning">{{ format_price($totalSpent ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body py-3">
                        <div class="text-muted small text-uppercase">{{ trans('Total Revenue') }}</div>
                        <div class="h5 font-weight-bold text-success">{{ format_price($totalRevenue ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body py-3">
                        <div class="text-muted small text-uppercase">{{ trans('Overall ROAS') }}</div>
                        <div class="h5 font-weight-bold text-primary">{{ number_format($overallRoas ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                {{ trans('ROAS Trends') }}
            </div>
            <div class="card-body">
                <div style="position: relative; height: 200px;">
                    <canvas id="roasTrendChartHistory" aria-label="{{ trans('ROAS Trends') }}" role="img"></canvas>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ trans('Date') }}</th>
                        <th>{{ trans('Total Spent') }}</th>
                        <th>{{ trans('Orders') }}</th>
                        <th>{{ trans('Revenue') }}</th>
                        <th>{{ trans('ROAS') }}</th>
                        <th>{{ trans('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($history ?? collect()) as $historyItem)
                        @php
                            $spent = (float) ($historyItem->total_spent ?? 0);
                            $ordersCount = (int) ($historyItem->orders_count ?? 0);
                            $revenue = (float) ($historyItem->revenue ?? 0);
                            $roas = $spent > 0 ? ($revenue / $spent) : 0;
                            $dateFormatted = '—';
                            if ($historyItem->date) {
                                try {
                                    if (is_string($historyItem->date)) {
                                        $dateFormatted = \Carbon\Carbon::parse($historyItem->date)->format('M d, Y');
                                    } elseif (is_object($historyItem->date) && method_exists($historyItem->date, 'format')) {
                                        $dateFormatted = $historyItem->date->format('M d, Y');
                                    }
                                } catch (\Exception $e) {
                                    $dateFormatted = $historyItem->date;
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ $dateFormatted }}</td>
                            <td class="text-warning font-weight-bold">{{ format_price($spent) }}</td>
                            <td>{{ $ordersCount > 0 ? $ordersCount : '—' }}</td>
                            <td class="text-success font-weight-bold">{{ format_price($revenue) }}</td>
                            <td><span class="badge badge-info">{{ number_format($roas, 2) }}</span></td>
                            <td>
                                <a class="btn btn-xs btn-info" href="{{ route('admin.ads.accounts.details.history.edit', [$selectedAccount->id, $accountDetail->id, $historyItem->id]) }}" title="{{ trans('Edit') }}"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">{{ trans('No history records found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($history) && $history->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
                <div class="text-muted small">
                    {{ trans('Showing') }} {{ $history->firstItem() ?? 0 }}-{{ $history->lastItem() ?? 0 }} {{ trans('of') }} {{ $history->total() }}
                </div>
                <nav>{{ $history->links() }}</nav>
            </div>
        @endif
    </div>
</div>

<script>window.roasChartDataHistory = {!! json_encode($chartData ?? []) !!};</script>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" crossorigin="anonymous"></script>
<script>
(function () {
    var data = typeof window.roasChartDataHistory !== 'undefined' ? window.roasChartDataHistory : [];
    var labels = Array.isArray(data) ? data.map(function (d) { return d.date || ''; }) : [];
    var roasValues = Array.isArray(data) ? data.map(function (d) { return typeof d.roas === 'number' ? d.roas : parseFloat(d.roas) || 0; }) : [];
    var canvas = document.getElementById('roasTrendChartHistory');
    if (!canvas || typeof Chart === 'undefined') return;
    new Chart(canvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: '{{ trans("ROAS") }}',
                data: roasValues,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.15)',
                fill: true,
                tension: 0.35,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            var v = ctx.parsed.y;
                            return (v != null ? v.toFixed(2) : '') + ' ROAS';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { maxRotation: 0, font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.06)' },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
})();
</script>
@endsection
