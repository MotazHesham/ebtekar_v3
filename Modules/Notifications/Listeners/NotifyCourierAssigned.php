<?php

namespace Modules\Notifications\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notifications\Services\NotificationDispatcher;
use Modules\Notifications\Services\ShippingNotificationMessages;
use Modules\Shipping\Events\ShipmentAssignedToCourier;

class NotifyCourierAssigned implements ShouldQueue
{
    public function handle(ShipmentAssignedToCourier $event): void
    {
        $shipment = $event->shipment;
        [$title, $body] = app(ShippingNotificationMessages::class)->courierAssigned($shipment, $event->courierLabel);

        app(NotificationDispatcher::class)->notifyUsers(
            'shipment.courier_assigned',
            $title,
            $body,
            route('admin.delivery-orders.show', $shipment),
            $shipment,
            meta: ['courier_id' => $event->courierId, 'actor_user_id' => $event->actorUserId]
        );
    }
}
