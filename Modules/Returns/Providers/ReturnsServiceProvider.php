<?php

namespace Modules\Returns\Providers;

use App\Contracts\Shipping\ReturnServiceContract;
use Illuminate\Support\ServiceProvider;
use Modules\Returns\Services\ReturnService;

class ReturnsServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Returns';

    protected string $moduleNameLower = 'returns';

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->loadViewsFrom(module_path($this->moduleName, 'Resources/views'), $this->moduleNameLower);
        $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(ReturnServiceContract::class, ReturnService::class);
    }
}
