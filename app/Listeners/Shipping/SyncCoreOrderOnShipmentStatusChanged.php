<?php

namespace App\Listeners\Shipping;

use App\Contracts\Shipping\OrderReference;
use App\Contracts\Shipping\OrderSnapshotProviderContract;
use Modules\Shipping\Events\ShipmentStatusChanged;

class SyncCoreOrderOnShipmentStatusChanged
{
    public function __construct(protected OrderSnapshotProviderContract $orderSnapshots)
    {
    }

    public function handle(ShipmentStatusChanged $event): void
    {
        $shipment = $event->shipment;

        $reference = new OrderReference(
            $shipment->orderable_type,
            (int) $shipment->orderable_id,
            $this->barcodePrefixFromMorph($shipment->orderable_type),
        );

        $this->orderSnapshots->syncDeliveryStatus($reference, $event->newStatus);
    }

    protected function barcodePrefixFromMorph(string $type): string
    {
        return match ($type) {
            'App\Models\Order'           => 'O',
            'App\Models\ReceiptSocial'   => 'S',
            'App\Models\ReceiptCompany'  => 'C',
            default                      => 'O',
        };
    }
}
