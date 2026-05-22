<?php

namespace Modules\Timeline\Providers;

use Illuminate\Support\ServiceProvider;

class TimelineServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Timeline';

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->bind(
            \App\Contracts\Shipping\TimelineRecorderContract::class,
            \Modules\Timeline\Services\TimelineRecorder::class
        );
    }
}
