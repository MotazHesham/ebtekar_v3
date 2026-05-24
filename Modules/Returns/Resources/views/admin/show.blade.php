@extends('layouts.admin')

@section('content')
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between">
            <span>{{ __('returns::titles.detail') }} #{{ $returnCase->id }}</span>
            <span class="badge badge-warning">{{ $returnCase->status_label }}</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>{{ __('cruds.deliveryOrder.fields.order_num') }}:</strong>
                        @if ($returnCase->shipment)
                            <a href="{{ route('admin.delivery-orders.show', $returnCase->shipment) }}">
                                {{ $returnCase->shipment->order_num }}
                            </a>
                        @else
                            —
                        @endif
                    </p>
                    <p><strong>{{ __('cruds.deliveryOrder.fields.return_reason') }}:</strong> {{ $returnCase->reason_label }}</p>
                    <p><strong>{{ __('returns::fields.note') }}:</strong> {{ $returnCase->note ?: '—' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>{{ __('cruds.deliveryOrder.fields.courier') }}:</strong>
                        {{ $returnCase->courier?->user?->name ?? '—' }}</p>
                    <p><strong>{{ __('returns::fields.shipment_status') }}:</strong>
                        {{ __('delivery_order_status.' . $returnCase->shipment_status) }}</p>
                    <p><strong>{{ __('global.created_at') }}:</strong> {{ $returnCase->created_at }}</p>
                </div>
            </div>

            @if ($returnCase->status === 'open')
                <form method="POST" action="{{ route('admin.returns.warehouse', $returnCase) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-info btn-sm">{{ __('returns::actions.warehouse') }}</button>
                </form>
            @endif
            @if (in_array($returnCase->status, ['open', 'warehouse_received']))
                <form method="POST" action="{{ route('admin.returns.close', $returnCase) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">{{ __('returns::actions.close') }}</button>
                </form>
            @endif
            @if ($canManage ?? false)
                <a href="{{ route('admin.returns.edit', $returnCase) }}" class="btn btn-info btn-sm">{{ __('global.edit') }}</a>
                @if ($returnCase->status === 'closed')
                    <form method="POST" action="{{ route('admin.returns.reopen', $returnCase) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">{{ __('returns::actions.reopen') }}</button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">{{ __('returns::titles.attachments') }}</div>
        <div class="card-body">
            <div class="row mb-3">
                @forelse ($returnCase->getMedia('return_proofs') as $media)
                    <div class="col-md-2 mb-2">
                        <a href="{{ $media->getUrl() }}" target="_blank">
                            <img src="{{ $media->getUrl('thumb') }}" class="img-fluid img-thumbnail" alt="">
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-muted">{{ __('returns::messages.no_attachments') }}</div>
                @endforelse
            </div>
            @if (in_array($returnCase->status, ['open', 'warehouse_received']))
                <form method="POST" action="{{ route('admin.returns.attachments', $returnCase) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="attachments[]" class="form-control" accept="image/*" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('returns::actions.upload') }}</button>
                </form>
            @endif
        </div>
    </div>

    <a href="{{ route('admin.returns.index') }}" class="btn btn-secondary">{{ __('global.back') }}</a>
@endsection
