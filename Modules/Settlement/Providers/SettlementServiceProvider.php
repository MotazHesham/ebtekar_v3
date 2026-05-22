<?php

namespace Modules\Settlement\Providers;

use App\Contracts\Shipping\SettlementServiceContract;
use Illuminate\Support\ServiceProvider;
use Modules\Settlement\Services\SettlementService;

class SettlementServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Settlement';

    protected string $moduleNameLower = 'settlement';

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->loadViewsFrom(module_path($this->moduleName, 'Resources/views'), $this->moduleNameLower);
        $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(SettlementServiceContract::class, SettlementService::class);
    }
}
