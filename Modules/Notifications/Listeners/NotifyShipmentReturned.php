<?php

namespace Modules\Notifications\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notifications\Services\NotificationDispatcher;
use Modules\Notifications\Services\ShippingNotificationMessages;
use Modules\Returns\Enums\ReturnReason;
use Modules\Returns\Events\ShipmentReturned;

class NotifyShipmentReturned implements ShouldQueue
{
    public function handle(ShipmentReturned $event): void
    {
        $shipment = $event->shipment;
        $reason   = ReturnReason::tryFrom($event->returnCase->reason)?->label() ?? $event->returnCase->reason;
        [$title, $body] = app(ShippingNotificationMessages::class)->returned($shipment, $reason);

        app(NotificationDispatcher::class)->notifyUsers(
            'shipment.returned',
            $title,
            $body,
            route('admin.returns.show', $event->returnCase),
            $shipment,
            meta: ['return_case_id' => $event->returnCase->id]
        );
    }
}
