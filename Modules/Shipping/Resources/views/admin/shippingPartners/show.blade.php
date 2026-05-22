@extends('layouts.admin')
@section('content')
    <div class="card mb-3">
        <div class="card-header">{{ $shippingPartner->name }}</div>
        <div class="card-body">
            <p><strong>{{ __('cruds.shippingPartner.fields.code') }}:</strong> {{ $shippingPartner->code }}</p>
            <p><strong>{{ __('cruds.user.fields.email') }}:</strong> {{ $shippingPartner->user?->email }}</p>
            <p><strong>{{ __('cruds.shippingPartner.fields.phone') }}:</strong> {{ $shippingPartner->phone }}</p>
            @if ($shippingPartner->internal_notes)
                <p><strong>{{ __('cruds.shippingPartner.fields.internal_notes') }}:</strong> {{ $shippingPartner->internal_notes }}</p>
            @endif
        </div>
    </div>

    <div class="row mb-3">
        @foreach ([
            'today_received' => 'primary',
            'today_delivered' => 'success',
            'on_delivery' => 'warning',
            'today_returns' => 'danger',
        ] as $key => $color)
            <div class="col-md-3">
                <div class="card text-white bg-{{ $color }}">
                    <div class="card-body">
                        <div class="text-value-lg">{{ $stats[$key] }}</div>
                        <div>{{ __('delivery.dashboard.' . $key) }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header">{{ __('cruds.deliverMan.title') }}</div>
        <div class="card-body">
            <ul class="list-group">
                @forelse ($shippingPartner->deliverMen as $courier)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $courier->user?->name }} ({{ $courier->user?->phone_number }})</span>
                        <span class="badge badge-{{ $courier->status === 'active' ? 'success' : 'secondary' }}">{{ $courier->status }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">—</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
