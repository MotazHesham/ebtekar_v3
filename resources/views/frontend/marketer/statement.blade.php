@extends('frontend.layout.app')

@section('content')
    <div class="breadcrumb-main">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2>Marketer Statement</h2>
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="{{ route('frontend.marketer.statement') }}">Statement</a></li>
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
                                <h2>{{ $marketer->name }} - Statement</h2>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-white border rounded">
                                        <small class="text-muted d-block">Total Credits</small>
                                        <h5 class="mb-0">{{ number_format($totalCredits, 2) }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-white border rounded">
                                        <small class="text-muted d-block">Total Debits</small>
                                        <h5 class="mb-0">{{ number_format($totalDebits, 2) }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-white border rounded">
                                        <small class="text-muted d-block">Current Balance</small>
                                        <h5 class="mb-0">{{ number_format($currentBalance, 2) }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 bg-white border rounded">
                                        <small class="text-muted d-block">Net Balance (Credits - Debits)</small>
                                        <h5 class="mb-0">{{ number_format($netBalance, 2) }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 bg-white border rounded">
                                        <small class="text-muted d-block">Total Transactions</small>
                                        <h5 class="mb-0">{{ number_format($transactionsCount) }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Balance After</th>
                                            <th>Reference</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $item)
                                            <tr>
                                                <td>{{ $item->created_at }}</td>
                                                <td>{{ $item->type }}</td>
                                                <td>{{ number_format((float) $item->amount, 2) }}</td>
                                                <td>{{ number_format((float) $item->balance_after, 2) }}</td>
                                                <td>{{ $item->reference_type }} #{{ $item->reference_id }}</td>
                                                <td>{{ $item->notes }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center">No transactions yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
