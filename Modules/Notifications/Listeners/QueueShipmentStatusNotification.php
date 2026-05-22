<?php

namespace Modules\Notifications\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notifications\Jobs\SendShipmentStatusNotificationJob;
use Modules\Shipping\Events\ShipmentStatusChanged;

class QueueShipmentStatusNotification implements ShouldQueue
{
    public function handle(ShipmentStatusChanged $event): void
    {
        SendShipmentStatusNotificationJob::dispatch(
            $event->shipment->id,
            $event->newStatus,
            $event->actorUserId,
            $event->note
        );
    }
}
