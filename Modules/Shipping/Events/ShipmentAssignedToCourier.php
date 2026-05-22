<?php

namespace Modules\Shipping\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Shipping\Entities\Shipment;

class ShipmentAssignedToCourier
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Shipment $shipment,
        public int $courierId,
        public string $courierLabel,
        public ?int $actorUserId = null,
    ) {
    }
}
