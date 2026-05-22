<?php

namespace Modules\Notifications\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\Notifications\Listeners\NotifyCourierAssigned;
use Modules\Notifications\Listeners\NotifyScanMismatch;
use Modules\Notifications\Listeners\NotifySettlementClosed;
use Modules\Notifications\Listeners\NotifyShipmentCreated;
use Modules\Notifications\Listeners\NotifyShipmentReturned;
use Modules\Notifications\Listeners\QueueShipmentStatusNotification;
use Modules\Returns\Events\ShipmentReturned;
use Modules\Settlement\Events\CourierSettlementClosed;
use Modules\Shipping\Events\ShipmentAssignedToCourier;
use Modules\Shipping\Events\ShipmentCreated;
use Modules\Shipping\Events\ShipmentStatusChanged;
use Modules\Tracking\Events\ScanMismatchDetected;

class NotificationsServiceProvider extends EventServiceProvider
{
    protected string $moduleName = 'Notifications';

    protected string $moduleNameLower = 'notifications';

    protected $listen = [
        ShipmentCreated::class => [
            NotifyShipmentCreated::class,
        ],
        ShipmentStatusChanged::class => [
            QueueShipmentStatusNotification::class,
        ],
        ShipmentAssignedToCourier::class => [
            NotifyCourierAssigned::class,
        ],
        ShipmentReturned::class => [
            NotifyShipmentReturned::class,
        ],
        CourierSettlementClosed::class => [
            NotifySettlementClosed::class,
        ],
        ScanMismatchDetected::class => [
            NotifyScanMismatch::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();

        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->loadViewsFrom(module_path($this->moduleName, 'Resources/views'), $this->moduleNameLower);
        $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        $this->mergeConfigFrom(module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower);
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
