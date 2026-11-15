<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Inventory\Services\InventoryLedger;
use Modules\Inventory\Services\ShopifyInventoryClient;

class InventoryServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Inventory';

    protected string $moduleNameLower = 'inventory';

    public function boot(): void
    {
        $this->mergeConfigFrom(module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower);
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->loadViewsFrom(module_path($this->moduleName, 'Resources/views'), $this->moduleNameLower);
        $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->singleton(InventoryLedger::class);
        $this->app->singleton(ShopifyInventoryClient::class);
    }
}
