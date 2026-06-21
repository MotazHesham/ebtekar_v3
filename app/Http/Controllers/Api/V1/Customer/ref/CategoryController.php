<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\FeaturedCategoryResource;
use App\Http\ResponseHelper;
use App\Models\ProductCategory; 
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function featuredCategories()
    {
        $categories = Cache::remember('featured_categories', config('panel.cache_time_medium'), function () {
            return ProductCategory::with('subCategories')->where('featured', 1)->take(6)->get();
        }); 
        
        return ResponseHelper::returnResource(FeaturedCategoryResource::collection($categories));
    }
}
