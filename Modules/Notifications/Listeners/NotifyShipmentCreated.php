<?php

namespace Modules\Notifications\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notifications\Services\NotificationDispatcher;
use Modules\Notifications\Services\ShippingNotificationMessages;
use Modules\Shipping\Events\ShipmentCreated;

class NotifyShipmentCreated implements ShouldQueue
{
    public function handle(ShipmentCreated $event): void
    {
        $shipment = $event->shipment;
        [$title, $body] = app(ShippingNotificationMessages::class)->created($shipment);

        app(NotificationDispatcher::class)->notifyUsers(
            'shipment.created',
            $title,
            $body,
            route('admin.delivery-orders.show', $shipment),
            $shipment,
            meta: ['actor_user_id' => $event->actorUserId]
        );
    }
}
