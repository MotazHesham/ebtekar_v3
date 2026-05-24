<table>
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __('cruds.deliveryOrder.fields.order_num') }}</th>
            <th>{{ __('cruds.deliveryOrder.fields.client_name') }}</th>
            <th>{{ __('cruds.deliveryOrder.fields.phone_number') }}</th>
            <th>{{ __('cruds.deliveryOrder.fields.governorate') }}</th>
            <th>{{ __('cruds.deliveryOrder.fields.region') }}</th>
            <th>{{ __('delivery.fields.full_address') }}</th>
            <th>{{ __('cruds.deliveryOrder.fields.status') }}</th>
            <th>{{ __('cruds.deliveryOrder.fields.shipping_partner') }}</th>
            <th>{{ __('cruds.deliveryOrder.fields.courier') }}</th>
            <th>{{ __('cruds.deliveryOrder.fields.remaining_cod') }}</th>
            <th>{{ __('cruds.deliveryOrder.fields.shipping_cost') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shipments as $index => $shipment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $shipment->order_num }}</td>
                <td>{{ $shipment->client_name }}</td>
                <td>{{ $shipment->phone_number }}</td>
                <td>{{ $shipment->governorate }}</td>
                <td>{{ $shipment->region }}</td>
                <td>{{ $shipment->full_address }}</td>
                <td>{{ $shipment->status_label }}</td>
                <td>{{ $shipment->shippingPartner?->name }}</td>
                <td>{{ $shipment->courier?->user?->name }}</td>
                <td>{{ $shipment->remaining_cod }}</td>
                <td>{{ $shipment->shipping_cost }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
