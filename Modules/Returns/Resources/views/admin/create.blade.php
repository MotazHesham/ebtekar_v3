@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">{{ __('returns::actions.register') }}</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.returns.store') }}">
                @csrf
                <div class="form-group">
                    <label>{{ __('cruds.deliveryOrder.title_singular') }}</label>
                    @if ($shipment)
                        <input type="hidden" name="shipment_id" value="{{ $shipment->id }}">
                        <p class="form-control-plaintext">
                            <a href="{{ route('admin.delivery-orders.show', $shipment) }}">{{ $shipment->order_num }}</a>
                            — {{ __('delivery_order_status.' . $shipment->status) }}
                        </p>
                    @else
                        <input type="number" name="shipment_id" class="form-control" value="{{ old('shipment_id') }}"
                            placeholder="Shipment ID" required>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ __('cruds.deliveryOrder.fields.return_reason') }}</label>
                    <select name="reason" class="form-control" required>
                        @foreach ($returnReasons as $key => $label)
                            <option value="{{ $key }}" @selected(old('reason') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ __('returns::fields.note') }}</label>
                    <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                </div>
                <div class="form-group">
                    <label>{{ __('returns::fields.shipment_status') }}</label>
                    <select name="shipment_status" class="form-control">
                        <option value="returned">{{ __('delivery_order_status.returned') }}</option>
                        <option value="refused">{{ __('delivery_order_status.refused') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('returns::actions.register') }}</button>
                <a href="{{ route('admin.returns.index') }}" class="btn btn-secondary">{{ __('global.back') }}</a>
            </form>
        </div>
    </div>
@endsection
