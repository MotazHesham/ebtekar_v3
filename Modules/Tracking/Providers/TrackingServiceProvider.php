<?php

namespace Modules\Tracking\Providers;

use Illuminate\Support\ServiceProvider;

class TrackingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Tracking', 'Database/Migrations'));
    }
}
