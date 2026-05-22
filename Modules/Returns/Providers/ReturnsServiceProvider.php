<?php

namespace Modules\Returns\Providers;

use Illuminate\Support\ServiceProvider;

class ReturnsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Returns', 'Database/Migrations'));
    }
}
