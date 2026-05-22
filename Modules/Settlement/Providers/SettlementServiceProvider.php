<?php

namespace Modules\Settlement\Providers;

use Illuminate\Support\ServiceProvider;

class SettlementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Settlement', 'Database/Migrations'));
    }
}
