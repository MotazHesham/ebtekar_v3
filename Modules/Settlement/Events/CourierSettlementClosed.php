<?php

namespace Modules\Settlement\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Settlement\Entities\Settlement;

class CourierSettlementClosed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Settlement $settlement,
        public ?int $actorUserId = null,
    ) {
    }
}
