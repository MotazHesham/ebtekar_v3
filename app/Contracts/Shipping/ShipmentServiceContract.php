<?php

namespace App\Contracts\Shipping;

use Modules\Shipping\Entities\Shipment;

interface ShipmentServiceContract
{
    public function createFromOrderReference(OrderReference $reference, ?int $shippingPartnerId = null, ?int $userId = null): Shipment;

    public function handoffToPartner(Shipment $shipment, int $shippingPartnerId, ?int $userId = null): Shipment;

    public function transitionStatus(Shipment $shipment, string $newStatus, ?int $userId = null, ?string $note = null): Shipment;

    public function assignCourier(Shipment $shipment, int $courierId, ?int $userId = null): Shipment;
}
