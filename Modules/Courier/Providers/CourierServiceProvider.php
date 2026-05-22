<?php

namespace Modules\Courier\Providers;

use Illuminate\Support\ServiceProvider;

class CourierServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Courier';

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->loadViewsFrom(module_path($this->moduleName, 'Resources/views'), 'courier');
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
