<?php

namespace Modules\Returns\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Returns\Entities\ReturnCase;
use Modules\Shipping\Entities\Shipment;

class ShipmentReturned
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Shipment $shipment,
        public ReturnCase $returnCase,
        public ?int $actorUserId = null,
    ) {
    }
}
