<?php

namespace Modules\Dispatch\Providers;

use App\Contracts\Shipping\DispatchAssignmentContract;
use Illuminate\Support\ServiceProvider;
use Modules\Dispatch\Services\DispatchAssignmentService;

class DispatchServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Dispatch';

    protected string $moduleNameLower = 'dispatch';

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->loadViewsFrom(module_path($this->moduleName, 'Resources/views'), $this->moduleNameLower);
        $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(DispatchAssignmentContract::class, DispatchAssignmentService::class);
    }
}
