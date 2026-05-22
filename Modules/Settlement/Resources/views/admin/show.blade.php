@extends('layouts.admin')

@section('content')
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between">
            <span>{{ __('settlement::titles.detail') }} #{{ $settlement->id }}</span>
            <span class="badge badge-secondary">{{ $settlement->status_label }}</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>{{ __('cruds.deliveryOrder.fields.courier') }}:</strong>
                        {{ $settlement->courier?->user?->name ?? '-' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>{{ __('settlement::fields.date') }}:</strong>
                        {{ $settlement->settlement_date?->format(config('panel.date_format')) }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>{{ __('settlement::fields.expected') }}:</strong>
                        {{ number_format((float) $settlement->expected_amount, 2) }}</p>
                </div>
            </div>
            @if ($settlement->status === \Modules\Settlement\Enums\SettlementStatus::Confirmed->value)
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>{{ __('settlement::fields.collected') }}:</strong>
                            {{ number_format((float) $settlement->collected_amount, 2) }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>{{ __('settlement::fields.difference') }}:</strong>
                            <span class="{{ $settlement->difference_amount < 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format((float) $settlement->difference_amount, 2) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>{{ __('settlement::fields.settled_by') }}:</strong>
                            {{ $settlement->settledBy?->name ?? '-' }}</p>
                    </div>
                </div>
                @if ($settlement->notes)
                    <p><strong>{{ __('settlement::fields.notes') }}:</strong> {{ $settlement->notes }}</p>
                @endif
            @endif
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">{{ __('settlement::titles.lines') }}</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('cruds.deliveryOrder.fields.order_num') }}</th>
                        <th>{{ __('cruds.deliveryOrder.fields.client_name') }}</th>
                        <th>{{ __('settlement::fields.expected') }}</th>
                        <th>{{ __('settlement::fields.line_status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($settlement->lines as $line)
                        <tr>
                            <td>
                                @if ($line->shipment)
                                    <a href="{{ route('admin.delivery-orders.show', $line->shipment) }}">
                                        {{ $line->shipment->order_num }}
                                    </a>
                                @else
                                    #{{ $line->delivery_order_id }}
                                @endif
                            </td>
                            <td>{{ $line->shipment?->client_name ?? '-' }}</td>
                            <td>{{ number_format((float) $line->expected_amount, 2) }}</td>
                            <td>{{ $line->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">{{ __('settlement::messages.no_lines') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($settlement->status === \Modules\Settlement\Enums\SettlementStatus::Pending->value && $settlement->lines->isNotEmpty())
        <div class="card">
            <div class="card-header">{{ __('settlement::actions.confirm') }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settlements.confirm', $settlement) }}">
                    @csrf
                    <div class="form-group">
                        <label>{{ __('settlement::fields.collected') }}</label>
                        <input type="number" step="0.01" min="0" name="collected_amount" class="form-control"
                            value="{{ old('collected_amount', $settlement->expected_amount) }}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('settlement::fields.notes') }}</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success">{{ __('settlement::actions.confirm') }}</button>
                </form>
            </div>
        </div>
    @endif

    <a href="{{ route('admin.settlements.index') }}" class="btn btn-secondary mt-2">{{ __('global.back') }}</a>
@endsection
