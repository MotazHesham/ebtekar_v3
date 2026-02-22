@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('Add New History Record') }} — {{ $accountDetail->name }}
        <a class="btn btn-default btn-sm float-right" href="{{ route('admin.ads.accounts.details.history', [$selectedAccount->id, $accountDetail->id]) }}">
            <i class="fa fa-arrow-left"></i> {{ trans('Back') }}
        </a>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="card mb-3 bg-light">
            <div class="card-body py-2">
                <div class="row text-muted small">
                    <div class="col-md-3"><strong>{{ trans('Original Balance') }}:</strong> <span class="text-primary">{{ format_price($originalBalance ?? 0) }}</span></div>
                    <div class="col-md-3"><strong>{{ trans('Total Spent') }}:</strong> <span class="text-warning">{{ format_price($totalSpent ?? 0) }}</span></div>
                    <div class="col-md-3"><strong>{{ trans('Current Balance') }}:</strong> <span class="text-success">{{ format_price($remainingBalance ?? 0) }}</span></div>
                    <div class="col-md-3"><strong>{{ trans('Projected Balance After Creation') }}:</strong> <span class="text-success" id="projectedBalance">{{ format_price($remainingBalance ?? 0) }}</span></div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.ads.accounts.details.history.store', [$selectedAccount->id, $accountDetail->id]) }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="date">{{ trans('Date') }}</label>
                        <input class="form-control {{ $errors->has('date') ? 'is-invalid' : '' }}" type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required>
                        @if($errors->has('date'))
                            <div class="invalid-feedback">{{ $errors->first('date') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="total_spent">{{ trans('Total Spent') }}</label>
                        <input class="form-control {{ $errors->has('total_spent') ? 'is-invalid' : '' }}" type="number" step="0.01" min="0" name="total_spent" id="total_spent" value="{{ old('total_spent', 0) }}" required oninput="updateProjectedBalance()">
                        @if($errors->has('total_spent'))
                            <div class="invalid-feedback">{{ $errors->first('total_spent') }}</div>
                        @endif
                        <span class="help-block">{{ trans('Maximum available:') }} {{ format_price($remainingBalance ?? 0) }}</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">{{ trans('Create') }}</button>
                <a class="btn btn-default" href="{{ route('admin.ads.accounts.details.history', [$selectedAccount->id, $accountDetail->id]) }}">{{ trans('Cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    const currentBalance = {{ $remainingBalance ?? 0 }};
    const currencySymbol = 'EGP';
    @php
        $symbolFormat = 1;
        $isSymbolBefore = in_array($symbolFormat, [1, 3]);
    @endphp
    const isSymbolBefore = {{ $isSymbolBefore ? 'true' : 'false' }};
    const decimals = 2;

    function updateProjectedBalance() {
        const newSpentInput = document.getElementById('total_spent');
        const projectedBalanceEl = document.getElementById('projectedBalance');
        if (!newSpentInput || !projectedBalanceEl) return;
        const newSpent = parseFloat(newSpentInput.value) || 0;
        const projectedBalance = currentBalance - newSpent;
        const formattedBalance = projectedBalance.toFixed(decimals);
        const formattedValue = isSymbolBefore ? currencySymbol + ' ' + formattedBalance : formattedBalance + ' ' + currencySymbol;
        projectedBalanceEl.textContent = formattedValue;
        projectedBalanceEl.className = projectedBalance < 0 ? 'text-danger' : 'text-success';
    }
</script>
@endsection
