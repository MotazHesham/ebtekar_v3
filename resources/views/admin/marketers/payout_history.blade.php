@extends('layouts.admin')
@section('content')
    <div class="card mb-3">
        <div class="card-header">Payout History Filters</div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.marketers.payout-history') }}">
                <div class="row">
                    <div class="form-group col-md-4">
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
                    <div class="form-group col-md-3">
                        <label for="from_date">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="to_date">To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary mr-2" type="submit">Filter</button>
                        <a class="btn btn-secondary" href="{{ route('admin.marketers.payout-history') }}">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <strong>Total Payouts</strong><br>
                    {{ number_format(abs((float) $totalPayouts), 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Payout Transactions</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Marketer</th>
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
                                <td>{{ $item->marketer->name ?? '-' }}</td>
                                <td>{{ number_format(abs((float) $item->amount), 2) }}</td>
                                <td>{{ number_format((float) $item->balance_after, 2) }}</td>
                                <td>{{ $item->reference_type }} #{{ $item->reference_id }}</td>
                                <td>{{ $item->notes }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No payout transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $transactions->links() }}
        </div>
    </div>
@endsection
