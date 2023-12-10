<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\HomeCategory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
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

        $site_settings = get_site_setting();

        View::composer('frontend.layout.header', function ($view) use($site_settings) {  
            $header_nested_categories = Cache::rememberForever('header_nested_categories_'.$site_settings->id, function () use ($site_settings){
                return Category::where('published',1)->where('website_setting_id',$site_settings->id)->with('sub_categories.sub_sub_categories')->get();
            }); 
            $view->with(['header_nested_categories' => $header_nested_categories]);
        });
        View::composer('frontend.layout.app', function ($view) use($site_settings) {   
            $view->with(['flash_deal_product' => Product::where('flash_deal',1)->where('website_setting_id',$site_settings->id)->where('published',1)->inRandomOrder()->first()]);
        });
        View::composer('frontend.layout.header', function ($view) use($site_settings) {   
            $view->with(['header_home_categories' => HomeCategory::where('website_setting_id',$site_settings->id)->with('category')->get()]);
        }); 
    }
}
