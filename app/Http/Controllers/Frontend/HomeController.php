<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Customer;
use App\Models\GeneralSetting;
use App\Models\HomeCategory;
use App\Models\Product;
use App\Models\ReceiptProduct;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

class HomeController extends Controller
{
    public function index()
    {
        // return Location::get('156.208.96.80');
        // $data = ReceiptProduct::find(341);
        // $data = $data->with(['product_in_receitps' => function($q){
        // }]);
            // $q->whereBetween('created_at',['2022-02-14 00:00:00','2023-04-25 00:00:00']);
            // $q->whereHas('receipt_social',function($query){
            //     $query->where('done',1);
            // });
            // $q->selectRaw('receipt_product_id, sum(quantity) as quantity,sum(total) as total')
            // ->groupBy('receipt_product_id');

        // ->with('product_in_receitps',function($q){
        //     $q->whereBetween('created_at',['2022-02-14 00:00:00','2023-04-25 00:00:00'])->whereHas('receipt_social',function($query){
        //         $query->where('done',1);
        //     })->selectRaw('receipt_product_id, sum(quantity) as quantity,sum(total) as total')
        //     ->groupBy('receipt_product_id');
        // });
        // return $data[5]->product_in_receitpsCount[0]['quantity'];
        // return $data->product_social_receipts()
        // ->whereBetween('created_at',['2022-02-14 00:00:00','2023-04-25 00:00:00'])
        // ->whereHas('receipt_social',function($query){
        //     $query->where('done',1);
        // })
        // ->selectRaw('receipt_product_id, sum(quantity) as quantity,sum(total) as total')->groupBy('receipt_product_id')->get();
        $sliders = Slider::with('media')->where('published',1)->get();
        $new_products = Product::with('media')->where('published',1)->orderBy('created_at','desc')->get()->take(10);
        $home_categories = HomeCategory::with('category.media')->orderBy('created_at','desc')->get();
        $freatured_categories = Category::with(['media','products' => function ($query) {
                                                $query->where('published', 1)->orderBy('created_at', 'desc')->take(15); 
                                            }])->where('featured',1)->orderBy('created_at','desc')->get();
        $banners_1 = Banner::with('media')->where('position',1)->where('published',1)->orderBy('created_at','desc')->get()->take(2);
        $best_selling_products = Product::with('media')->where('published',1)->orderBy('num_of_sale','desc')->get()->take(10);
        return view('frontend.home', compact('sliders','new_products','home_categories','freatured_categories','banners_1','best_selling_products'));
    }

    public function about(){
        $general_settings = GeneralSetting::first();
        return view('frontend.about',compact('general_settings'));
    }

    public function events(){
        return view('frontend.calender');
    }

    public function dashboard(){
        $user = Auth::user();
        return view('frontend.dashboard',compact('user'));
    }
}
