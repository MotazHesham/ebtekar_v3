<?php

namespace Modules\Tracking\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScanMismatchDetected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $barcode,
        public ?int $expectedPartnerId,
        public ?int $actualPartnerId,
        public string $reason,
        public ?int $actorUserId = null,
    ) {
    }
}
