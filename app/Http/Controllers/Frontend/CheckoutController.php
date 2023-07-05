<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PayMobController;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Customer;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class CheckoutController extends Controller
{
    public function payment_select(){
        if(!session('cart')){
            alert("قم بأضافة منتجات الي السلة أولا",'','warning');
            return redirect()->route('home');
        }

        $countries = Country::where('status',1)->where('website',1)->get()->groupBy('type'); 
        return view('frontend.checkout',compact('countries'));
    }

    public function checkout(Request $request){

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'nullable|email|unique:users',
            'phone_number' => 'required|regex:' . config('panel.phone_number_format') . '|size:' . config('panel.phone_number_size'),
            'phone_number_2' => 'nullable|regex:' . config('panel.phone_number_format') . '|size:' . config('panel.phone_number_size'),
            'shipping_address' => 'required',
            'payment_option' => 'required|in:cash_on_delivery,paymob',
        ]);


        try{

            DB::beginTransaction();
            $site_settings = get_site_setting();
            
            if(auth()->check()){
                $user = Auth::user();
            }else{
                if($request->has('create_account')){ 
                    $user = User::create([
                        'name' => $request->first_name . ' ' . $request->last_name,
                        'email' => $request->email,
                        'phone_number' => $request->phone_number,
                        'address' => $request->shipping_address,
                        'password' => bcrypt($request->password),
                        'user_type' => 'customer',
                        'approved' => 1,
                        'verified' => 1,
                        'website_setting_id' => $site_settings->id,
                    ]);
                    Customer::create([
                        'user_id' => $user->id,
                    ]);
                }else{
                    $user = null;
                }
            }

            if(!session('cart')){
                alert("قم بأضافة منتجات الي السلة أولا",'','warning');
                return redirect()->route('home');
            }
            
            if($request->discount_code != null){
                $seller = Seller::where('discount_code',$request->discount_code)->first();
                if(!$seller || $seller->discount < 0){ 
                    alert("خطأ في كود الخصم",'','warning');
                    return redirect()->route('frontend.payment_select');
                }
            }
            
            if ($request->payment_option != null) {

                if($user && $user->user_type == 'seller'){
                    $code_z = Order::withoutGlobalScope('completed')->where('order_type','seller')->where('website_setting_1',$site_settings->id)->latest()->first()->order_num ?? 0;
                }else{
                    $code_z = Order::withoutGlobalScope('completed')->where('order_type','customer')->where('website_setting_1',$site_settings->id)->latest()->first()->order_num ?? 0;
                }
                $last_order_code = intval(str_replace('#','',strrchr($code_z,"#")));


                $order = new Order;
                $country = Country::findOrFail($request->country_id);

                $order->user_id  = $user->id ?? null;
                $order->shipping_country_id  = $country->id; 
                $order->website_setting_id  = $site_settings->id; 
                $order->shipping_country_cost  = $country->cost;
                $order->phone_number  = $request->phone_number;
                $order->phone_number_2  = $request->phone_number_2;
                $order->shipping_address = $request->shipping_address;
                $order->payment_type = $request->payment_option;

                if($user && $user->user_type == 'seller'){
                    $order->client_name = $request->client_name;
                    $order->date_of_receiving_order = strtotime($request->date_of_receiving_order);
                    $order->excepected_deliverd_date = strtotime($request->excepected_deliverd_date);
                    $order->deposit_type = $request->deposit_type;
                    $order->deposit_amount = $request->deposit_amount;
                    $order->free_shipping = $request->free_shipping;
                    $order->shipping_cost_by_seller = $request->shipping_cost_by_seller;
                    $order->free_shipping_reason = $request->free_shipping_reason;
                    $order->total_cost_by_seller = $request->total_cost_by_seller;
                    $order->order_type = 'seller';
                }else{
                    $order->client_name = $request->first_name . ' ' . $request->last_name;
                    $order->order_type = 'customer';
                }

                if($site_settings->id == 2){
                    $str = 'ertegal-';
                }elseif($site_settings->id == 3){
                    $str = 'figures-';
                }else{ 
                    $str = 'ebtekar-';
                }

                if($user && $user->user_type == 'seller'){
                    $order->order_num = $str . 'seller#' . ($last_order_code + 1);
                }else{
                    $order->order_num = $str . 'customer#' . ($last_order_code + 1);
                }
                
                $order->save();

                $total_commission = 0;
                $total_cost = 0;
                $order_items = [];

                foreach(session('cart') as $cartItem){

                    // increse number of sales in product
                    $product = Product::findOrFail($cartItem['product_id']);
                    $product->num_of_sale += $cartItem['quantity'];
                    $product->save();

                    if($product->variant_product == 1 && $cartItem['variation'] != null){
                        //remove requested quantity from stock
                        $product_stock = $product->stocks()->where('variant', $cartItem['variation'])->first();
                        if($product_stock){
                            $product_stock->stock -= $cartItem['quantity'];
                            $product_stock->save();
                        } 
                    }else {
                        //remove requested quantity from stock
                        $product->current_stock -= $cartItem['quantity'];
                        $product->save();
                    }

                    $prices = product_price_in_cart($cartItem['quantity'],$cartItem['variation'],$product);
                    $total_cost += ($prices['price']['value'] * $cartItem['quantity'] );

                    

                    //add commission to seller
                    $total_commission += $prices['commission']; 
                    $order_items [] = [
                        'order_id' => $order->id,
                        'product_id' => $cartItem['product_id'],
                        'variation' => $cartItem['variation'],
                        'link' => $cartItem['link'],
                        'description' => $cartItem['description'],
                        'commission' => $prices['commission'] ?? 0,
                        'email_sent' => $cartItem['email_sent'] ?? 0,
                        'quantity' => $cartItem['quantity'],
                        'price' => $prices['price']['value'],
                        'total_cost' => $total_cost,
                        'photos' => $cartItem['photos'] ?? null, 
                        'pdf' => $cartItem['pdf'] ?? null,
                    ];
                }

                OrderDetail::insert($order_items);



                if($request->discount_code != null){
                    $seller = Seller::where('discount_code',$request->discount_code)->first();
                    if($seller && $seller->discount > 0){
                        $discount_cost = $total_cost * ($seller->discount / 100);
                        $total_cost -= $discount_cost;
                        $order->discount = $discount_cost;
                        $order->discount_code = $request->discount_code;
                        $order->social_user_id = $seller->user_id; // it stands for the owner of the discount code
                    }else{  
                        throw ValidationException::withMessages(['discount_code' => 'Discount Code Faild']);
                    }
                }
                $order->symbol = $prices['price']['symbol'];
                $order->commission = $total_commission;
                $order->total_cost = $total_cost;
                $order->save();


                if($user && $user->user_type == 'seller'){
                    $seller = Seller::where('user_id',$order->user_id)->first();
                    $seller->order_in_website += 1 ;
                    $seller->save();
                }

                if($request->payment_option == 'cash_on_delivery'){
                    if($this->checkout_done($order->id,'unpaid')){
                        toast("Your order has been placed successfully",'success');
                        if($request->has('create_account')){
                            Auth::login($user);
                        }
                        DB::commit();
                        return redirect()->route('frontend.orders.success',$order->id);
                    }
                }elseif($request->payment_option == 'paymob'){
                    return 'Not Available right now';
                    // $paymob = new PayMobController;
                    // return $paymob->checkingOut('1602333','242734',$order->id,$request->first_name,$request->last_name,$request->phone_number);
                }
            }else {
                toast("Try Again",'error');
                return redirect()->route('home');
            }
        }catch (\Exception $ex){
            DB::rollBack();
            return $ex;
            toast("SomeThing Went Wrong!",'error');
            return redirect()->route('home');
        }
    }


    //redirects to this method after a successfull checkout
    public function checkout_done($order_id, $payment)
    { 
        $order = Order::withoutGlobalScope('completed')->find($order_id);
        $order->payment_status = $payment;
        $order->completed = 1;
        if($payment == 'paid'){
            $order->deposit_amount = $order->calc_total() - $order->calc_discount();
        }
        $order->save();

        session()->put('cart',null);

        // $title = $order->order_num;
        // $body = 'طلب جديد';
        // UserAlert::create([
        //     'alert_text' => $title . ' ' . $body,
        //     'alert_link' => route('admin.orders.show', encrypt($order->id)),
        //     'type' => 'designs',
        //     'user_id' => 0 ,
        // ]);

        // $tokens = User::whereNotNull('device_token')->whereIn('user_type',['staff','admin'])->where(function ($query) {
        //                                                 $query->where('notification_show',1)
        //                                                         ->orWhere('user_type','admin');
        //                                             })->pluck('device_token')->all();
        // $push_controller = new PushNotificationController();
        // $push_controller->sendNotification($title, $body, $tokens,route('admin.orders.show', encrypt($order->id)));

        //sending mail
        // $generalsetting = GeneralSetting::first();
        // $body = view('frontend.partials.send_confirmed_order_email', compact('order','generalsetting'))->render();
        // $this->sendEmail( $body , $user->email,"New Order From EbtekarStore.net ".$order->order_num);


        return 1;
    }
}
