<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\SendFacebookEventJob;
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
use App\Services\FacebookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Stevebauman\Location\Facades\Location;

class HomeController extends Controller
{
    public function sitemap(){
        $site_settings = get_site_setting();
        return response()->file(public_path($site_settings->sitemap_link_seo));
    }
    public function pageViewEvent(){ 
        $site_settings = get_site_setting();
        if($site_settings->fb_pixel_id){ 
            $userData = getUserDataForConersionApi();
            SendFacebookEventJob::dispatch(['event_source_url' => url()->current()], $site_settings->id,$userData,'pageview');  
        }
        return response()->json(null,200);
    }
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
        $home_categories = Cache::rememberForever('home_categories_'.$site_settings->id, function () use ($site_settings) {
            return HomeCategory::where('website_setting_id', $site_settings->id)->with('category.media')->orderBy('created_at', 'desc')->get();
        });

        $banners_1 = Cache::rememberForever('home_banners_1_'.$site_settings->id, function () use ($site_settings) {
            return Banner::where('website_setting_id', $site_settings->id)->with('media')->where('position', 1)->where('published', 1)->orderBy('updated_at', 'desc')->get()->take(2);
        });
        $featured_categories  = Category::where('website_setting_id', $site_settings->id)->where('published', 1)->where('featured', 1)->with('media')->get();
        $first_featured_category_id = $featured_categories[0]->id ?? 0;
        
        $disable_subscribe = true;
        $home_css = true;
        return view('frontend.home', compact('sliders','disable_subscribe','home_css',  'home_categories', 'banners_1', 'featured_categories','first_featured_category_id'));
    }

    public function new_products(){
        $site_settings = get_site_setting();
        $new_products = Cache::rememberForever('home_new_products_'.$site_settings->id, function () use ($site_settings) {
            return Product::where('website_setting_id', $site_settings->id)->with('media','category')->where('published', 1)->where('todays_deal', 1)->orderBy('created_at', 'desc')->take(10)->get();
        });
        
        return view('frontend.ajax-load.new-products', compact('new_products'));
    }

    public function featured_categories(Request $request){ 
        
        $products = Product::where('category_id',$request->category_id)
                            ->where('published', 1)->where('featured', 1)
                            ->orderBy('created_at', 'desc')->with('category','media')->take(8)->get();
        
        return view('frontend.ajax-load.featured-categories', compact('products'));
    }

    public function best_selling_products(){
        $site_settings = get_site_setting();
        $best_selling_products = Cache::rememberForever('home_best_selling_products_'.$site_settings->id, function () use ($site_settings) {
            return Product::where('website_setting_id', $site_settings->id)->with('media','category')->where('published', 1)->orderBy('num_of_sale', 'desc')->take(10)->get();
        }); 
        
        return view('frontend.ajax-load.best-selling', compact('best_selling_products'));
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
        $this->validate($request,[ 
            'first_name' => 'string|required|max:255',
            'last_name' => 'string|required|max:255',
            'email' => 'string|required|max:255',
            'phone_number' => 'string|required|max:255',
            'message' => 'string|required|max:255', 
            'g-recaptcha-response' => 'required',
        ]);
        Contactu::create($request->all());
        alert('Your message sent successfully', '', 'success');
        return redirect()->route('frontend.contact');
    }

    public function events()
    {
        return view('frontend.calender');
    }
}
