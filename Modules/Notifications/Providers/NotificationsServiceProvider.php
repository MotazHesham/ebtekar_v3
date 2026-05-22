<?php

namespace Modules\Notifications\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\Notifications\Listeners\QueueShipmentStatusNotification;
use Modules\Shipping\Events\ShipmentAssignedToCourier;
use Modules\Shipping\Events\ShipmentCreated;
use Modules\Shipping\Events\ShipmentStatusChanged;

class NotificationsServiceProvider extends EventServiceProvider
{
    protected $listen = [
        ShipmentCreated::class => [
            // QueueShipmentCreatedNotification::class,
        ],
        ShipmentStatusChanged::class => [
            QueueShipmentStatusNotification::class,
        ],
        ShipmentAssignedToCourier::class => [
            // QueueCourierAssignmentNotification::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
