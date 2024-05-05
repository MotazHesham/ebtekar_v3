<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Contactu;
use App\Models\Customer;
use App\Models\GeneralSetting;
use App\Models\HomeCategory;
use App\Models\Police;
use App\Models\Product;
use App\Models\ReceiptProduct;
use App\Models\Slider;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Stevebauman\Location\Facades\Location;

class HomeController extends Controller
{
    public function webxr($id)
    {
        $product = Product::findOrFail($id);  
        return view('frontend.partials.webxr',compact('product'));    
    }
    public function index()
    {   
        $site_settings = get_site_setting();
        $sliders = Cache::rememberForever('home_silders_'.$site_settings->id, function () use ($site_settings) {
            return Slider::where('website_setting_id', $site_settings->id)->with('media')->where('published', 1)->get();
        });
        $new_products = Cache::rememberForever('home_new_products_'.$site_settings->id, function () use ($site_settings) {
            return Product::where('website_setting_id', $site_settings->id)->with('media','category')->where('published', 1)->where('todays_deal', 1)->orderBy('created_at', 'desc')->take(10)->get();
        });
        $home_categories = Cache::rememberForever('home_categories_'.$site_settings->id, function () use ($site_settings) {
            return HomeCategory::where('website_setting_id', $site_settings->id)->with('category.media')->orderBy('created_at', 'desc')->get();
        });
        $freatured_categories =  Cache::rememberForever('freatured_categories_'.$site_settings->id, function () use ($site_settings) {

            $categories = Category::where('website_setting_id', $site_settings->id)->where('published', 1)->where('featured', 1)->with(['media','products.media','products.category', 'products' => function ($query) {
                $query->where('published', 1)->where('featured', 1)->orderBy('created_at', 'desc');
            }])->orderBy('created_at', 'desc')->get();

            $categories->transform(function ($category) {
                $category->products = $category->products->chunk(3);
                return $category;
            });

            return $categories;
        });

        $banners_1 = Cache::rememberForever('home_banners_1_'.$site_settings->id, function () use ($site_settings) {
            return Banner::where('website_setting_id', $site_settings->id)->with('media')->where('position', 1)->where('published', 1)->orderBy('updated_at', 'desc')->get()->take(2);
        });
        $best_selling_products = Cache::rememberForever('home_best_selling_products_'.$site_settings->id, function () use ($site_settings) {
            return Product::where('website_setting_id', $site_settings->id)->with('media','category')->where('published', 1)->orderBy('num_of_sale', 'desc')->take(10)->get();
        });
        return view('frontend.home', compact('sliders', 'new_products', 'home_categories', 'freatured_categories', 'banners_1', 'best_selling_products'));
    }

    public function about()
    {
        $site_settings = get_site_setting();
        return view('frontend.about', compact('site_settings'));
    }

    public function policies($policy)
    {
        $site_settings = get_site_setting();
        $policy = Police::where('name', $policy)->where('website_setting_id',$site_settings->id)->first();
        return view('frontend.policy', compact('policy'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function contact_store(Request $request)
    {
        Contactu::create($request->all());
        alert('Your message sent successfully', '', 'success');
        return redirect()->route('frontend.contact');
    }

    public function events()
    {
        return view('frontend.calender');
    }
}
