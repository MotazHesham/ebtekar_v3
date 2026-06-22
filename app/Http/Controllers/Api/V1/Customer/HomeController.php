<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\BannerResource;
use App\Http\Resources\V1\Customer\HomeCategoryResource;
use App\Http\Resources\V1\Customer\ProductListResource;
use App\Http\Resources\V1\Customer\SliderResource;
use App\Http\ResponseHelper;
use App\Models\Banner;
use App\Models\HomeCategory;
use App\Models\Product;
use App\Models\Slider;

class HomeController extends Controller
{
    public function headlineBar()
    {
        return ResponseHelper::returnResponse('', [
            [
                'title' => 'شحن مجاني للطلبات فوق 900ج - توصيل خلال 5-7 أيام عمل',
                'link' => 'https://www.google.com',
                'type' => 'external_url',
            ],
            [
                'title' => 'تسوق منتجاتنا الأكثر مبيعاً - تسوق الآن',
                'link' => '/products',
                'type' => 'product_list',
            ],
        ]);
    }

    public function trustBadges()
    {
        return ResponseHelper::returnResponse('', [
            [
                'position' => 1,
                'title' => 'شحن سريع',
                'subtitle' => '5-7 أيام عمل',
            ],
            [
                'position' => 2,
                'title' => 'دفع آمن',
                'subtitle' => 'دفع آمن وموثوق',
            ],
            [
                'position' => 3,
                'title' => 'تقييم 5',
                'subtitle' => 'علي جوجل',
            ],
            [
                'position' => 4,
                'title' => 'عروض حصرية',
                'subtitle' => 'أفضل الأسعار',
            ],
        ]);
    }

    public function sliders()
    {
        $site_settings = get_site_setting();
        $sliders = Slider::where('published', 1)
            ->where('website_setting_id', $site_settings->id)
            ->with('media')
            ->take(5)
            ->get();

        return ResponseHelper::returnResource(
            SliderResource::collection($sliders)
        );
    }

    public function homeCategories()
    {
        $site_settings = get_site_setting();
        $homeCategories = HomeCategory::where('website_setting_id', $site_settings->id)
            ->with('category.media')
            ->whereHas('category')
            ->orderBy('created_at', 'desc')
            ->get();

        return ResponseHelper::returnResource(
            HomeCategoryResource::collection($homeCategories)
        );
    }

    public function bestSellingProducts()
    {
        $site_settings = get_site_setting();
        $products = Product::where('published', 1)
            ->withCount('reviews')
            ->with('media')
            ->where('website_setting_id', $site_settings->id)
            ->where('num_of_sale', '>', 0)
            ->orderBy('num_of_sale', 'desc')
            ->take(10)
            ->get();

        return ResponseHelper::returnResource(
            ProductListResource::collection($products)
        );
    }

    public function newProducts()
    {
        $site_settings = get_site_setting();
        $products = Product::where('published', 1)
            ->withCount('reviews')
            ->with('media', 'category')
            ->where('website_setting_id', $site_settings->id)
            ->where('todays_deal', 1)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return ResponseHelper::returnResource(
            ProductListResource::collection($products)
        );
    }

    public function flashDealsProducts()
    {
        $site_settings = get_site_setting();
        $products = Product::where('published', 1)
            ->with('media', 'category')
            ->where('website_setting_id', $site_settings->id)
            ->where('flash_deal', 1)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return ResponseHelper::returnResource(
            ProductListResource::collection($products)
        );
    }

    public function banner()
    {
        $site_settings = get_site_setting();
        $banner = Banner::where('website_setting_id', $site_settings->id)
            ->with('media')
            ->where('position', 1)
            ->where('published', 1)
            ->orderBy('updated_at', 'desc')
            ->latest()
            ->firstOrFail();

        return ResponseHelper::returnResource(
            BannerResource::make($banner)
        );
    }
}
