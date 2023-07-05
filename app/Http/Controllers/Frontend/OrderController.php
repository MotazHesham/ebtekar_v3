<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orders(){
        $site_settings = get_site_setting();
        global $website_setting_id;
        $website_setting_id = $site_settings->id;
        $orderDetails = OrderDetail::with('order','product')->whereHas('order' ,function($query){
            $query->where('user_id',Auth::id())->where('website_setting_id',$GLOBALS['website_setting_id']);
        })->orderBy('created_at','desc')->paginate(10);
        return view('frontend.orders',compact('orderDetails'));
    }



    public function track($id){
        $order = Order::findOrFail($id);
        return view('frontend.order-track',compact('order'));
    }



    public function success($id){
        $order = Order::findOrFail($id);
        return view('frontend.order-success',compact('order'));
    }
}
