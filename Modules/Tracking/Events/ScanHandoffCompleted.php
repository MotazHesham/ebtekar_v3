<?php

namespace Modules\Tracking\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Shipping\Entities\Shipment;

class ScanHandoffCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Shipment $shipment,
        public int $shippingPartnerId,
        public ?int $actorUserId = null,
        public string $barcode = '',
    ) {
    }
}
