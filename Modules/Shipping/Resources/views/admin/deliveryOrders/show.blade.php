@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('cruds.deliveryOrder.title_singular') }} #{{ $deliveryOrder->order_num }}</span>
                    <div>
                        @can('delivery_return_access')
                            @if (!in_array($deliveryOrder->status, ['returned', 'refused', 'closed']))
                                <a href="{{ route('admin.returns.create', ['shipment_id' => $deliveryOrder->id]) }}"
                                    class="btn btn-sm btn-warning mr-2">{{ __('returns::actions.register') }}</a>
                            @endif
                        @endcan
                        <span class="badge badge-info">{{ $deliveryOrder->status_label }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>{{ __('cruds.deliveryOrder.fields.client_name') }}:</strong> {{ $deliveryOrder->client_name }}</p>
                            <p><strong>{{ __('cruds.deliveryOrder.fields.phone_number') }}:</strong> {{ $deliveryOrder->phone_number }}</p>
                            <p><strong>{{ __('cruds.deliveryOrder.fields.governorate') }}:</strong> {{ $deliveryOrder->governorate }}</p>
                            <p><strong>{{ __('cruds.deliveryOrder.fields.region') }}:</strong> {{ $deliveryOrder->region }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>{{ __('cruds.deliveryOrder.fields.cod_amount') }}:</strong> {{ number_format((float) $deliveryOrder->cod_amount, 2) }}</p>
                            <p><strong>{{ __('cruds.deliveryOrder.fields.deposit_amount') }}:</strong> {{ number_format((float) $deliveryOrder->deposit_amount, 2) }}</p>
                            <p><strong>{{ __('cruds.deliveryOrder.fields.remaining_cod') }}:</strong> {{ number_format((float) $deliveryOrder->remaining_cod, 2) }}</p>
                            <p><strong>{{ __('cruds.deliveryOrder.fields.shipping_cost') }}:</strong> {{ number_format((float) $deliveryOrder->shipping_cost, 2) }}</p>
                            <p><strong>{{ __('cruds.deliveryOrder.fields.pending_since') }}:</strong> {{ $deliveryOrder->pending_since ?? '-' }}</p>
                        </div>
                    </div>
                    @if ($deliveryOrder->orderable && method_exists($deliveryOrder->orderable, 'get_products_details'))
                        <hr>
                        <p><strong>{{ __('global.products') ?? 'Products' }}:</strong></p>
                        <p>{{ $deliveryOrder->orderable->get_products_details() }}</p>
                    @endif
                    @if ($deliveryOrder->orderable?->note)
                        <hr>
                        <p><strong>{{ __('cruds.deliveryOrder.fields.notes') }} ({{ __('global.client') ?? 'Client' }}):</strong> {{ $deliveryOrder->orderable->note }}</p>
                    @endif
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">{{ __('cruds.deliveryOrder.fields.timeline') }}</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($deliveryOrder->timelineEvents as $event)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $event->user?->name ?? 'System' }}</strong>
                                    <small class="text-muted">{{ $event->created_at }}</small>
                                </div>
                                @if ($event->old_status && $event->new_status)
                                    <div class="text-muted small">
                                        {{ __('delivery_order_status.' . $event->old_status) }}
                                        →
                                        {{ __('delivery_order_status.' . $event->new_status) }}
                                    </div>
                                @endif
                                @if ($event->body)
                                    <div>{{ $event->body }}</div>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item text-muted">{{ __('global.no_results') ?? 'No events yet' }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">{{ __('cruds.deliveryOrder.fields.notes') }}</div>
                <div class="card-body">
                    @foreach ($deliveryOrder->notes as $note)
                        <div class="border rounded p-2 mb-2">
                            <strong>{{ $note->user?->name }}</strong>
                            <small class="text-muted">{{ $note->created_at }}</small>
                            <p class="mb-0">{{ $note->body }}</p>
                            @foreach ($note->replies as $reply)
                                <div class="ml-3 mt-2 border-left pl-2">
                                    <strong>{{ $reply->user?->name }}</strong>
                                    <small class="text-muted">{{ $reply->created_at }}</small>
                                    <p class="mb-0">{{ $reply->body }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <form method="POST" action="{{ route('admin.delivery-orders.notes.store', $deliveryOrder) }}">
                        @csrf
                        <div class="form-group">
                            <textarea name="body" class="form-control" rows="2" required placeholder="{{ __('cruds.deliveryOrder.fields.notes') }}"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('global.save') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @can('delivery_order_edit')
                <div class="card mb-3">
                    <div class="card-header">{{ __('global.edit') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.delivery-orders.update', $deliveryOrder) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>{{ __('cruds.deliveryOrder.fields.status') }}</label>
                                <select name="status" class="form-control" required>
                                    @foreach ($statuses as $statusKey)
                                        <option value="{{ $statusKey }}" @selected($deliveryOrder->status === $statusKey)>
                                            {{ __('delivery_order_status.' . $statusKey) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('cruds.deliveryOrder.fields.shipping_partner') }}</label>
                                <select name="shipping_partner_id" class="form-control">
                                    <option value="">{{ __('global.pleaseSelect') }}</option>
                                    @foreach ($shippingPartners as $id => $name)
                                        <option value="{{ $id }}" @selected($deliveryOrder->shipping_partner_id == $id)>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('cruds.deliveryOrder.fields.courier') }}</label>
                                <select name="deliver_man_id" class="form-control">
                                    <option value="">{{ __('global.pleaseSelect') }}</option>
                                    @foreach ($deliverMen as $id => $name)
                                        <option value="{{ $id }}" @selected($deliveryOrder->deliver_man_id == $id)>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group return-fields" style="{{ in_array($deliveryOrder->status, ['returned', 'refused']) ? '' : 'display:none' }}">
                                <label>{{ __('cruds.deliveryOrder.fields.return_reason') }}</label>
                                <select name="return_reason" class="form-control">
                                    @foreach ($returnReasons as $key => $label)
                                        <option value="{{ $key }}" @selected($deliveryOrder->return_reason === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <textarea name="return_note" class="form-control mt-2" rows="2">{{ $deliveryOrder->return_note }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">{{ __('global.update') }}</button>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $('select[name=status]').on('change', function() {
            const show = ['returned', 'refused'].includes($(this).val());
            $('.return-fields').toggle(show);
        });
    </script>
@endsection
