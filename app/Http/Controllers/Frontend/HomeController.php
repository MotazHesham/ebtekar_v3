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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stevebauman\Location\Facades\Location;

class HomeController extends Controller
{ 
    public function index()
    {  
        $site_settings = get_site_setting();
        $sliders = Slider::where('website_setting_id',$site_settings->id)->with('media')->where('published',1)->get();
        $new_products = Product::where('website_setting_id',$site_settings->id)->with('media')->where('published',1)->where('todays_deal',1)->orderBy('created_at','desc')->get()->take(10);
        $home_categories = HomeCategory::where('website_setting_id',$site_settings->id)->with('category.media')->orderBy('created_at','desc')->get();
        $freatured_categories = Category::where('website_setting_id',$site_settings->id)->with(['media','products' => function ($query) {
                                                $query->where('published', 1)->orderBy('created_at', 'desc')->get()->take(30); 
                                            }])->where('featured',1)->orderBy('created_at','desc')->get();
        $banners_1 = Banner::where('website_setting_id',$site_settings->id)->with('media')->where('position',1)->where('published',1)->orderBy('updated_at','desc')->get()->take(2);
        $best_selling_products = Product::where('website_setting_id',$site_settings->id)->with('media')->where('published',1)->orderBy('num_of_sale','desc')->get()->take(10);
        return view('frontend.home', compact('sliders','new_products','home_categories','freatured_categories','banners_1','best_selling_products'));
    }

    public function about(){
        $site_settings = get_site_setting(); 
        return view('frontend.about',compact('site_settings'));
    }

    public function policies($policy){ 
        $policy = Police::where('name',$policy)->first();
        return view('frontend.policy',compact('policy'));
    }

    public function contact(){
        return view('frontend.contact');
    } 

    public function contact_store(Request $request){
        Contactu::create($request->all());
        alert('Your message sent successfully','','success');
        return redirect()->route('frontend.contact');
    }

    public function events(){
        return view('frontend.calender');
    }

}
