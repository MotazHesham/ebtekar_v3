<?php

namespace App\Providers;

use App\Models\HomeCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive(); // use the bootstrap 5 for pagination

        // Model::preventLazyLoading --------------
        // important Note (disable it on production) How ?
        // - app()->isProduction() it refers to .env file to (APP_ENV)
        Model::preventLazyLoading(!app()->isProduction()); // while development it show error if you try to lazy loading query 
    }
}
