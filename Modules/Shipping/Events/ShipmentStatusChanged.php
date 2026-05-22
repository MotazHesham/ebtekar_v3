<?php

namespace Modules\Shipping\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Shipping\Entities\Shipment;

class ShipmentStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Shipment $shipment,
        public ?string $oldStatus,
        public string $newStatus,
        public ?int $actorUserId = null,
        public ?string $note = null,
    ) {
    }
}
