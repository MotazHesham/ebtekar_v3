<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PayMobController;
use App\Models\Cart;
use App\Models\Country;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function payment_select(){
        $cart = Cart::with('product')->where('user_id',Auth::id())->orderBy('created_at','desc')->get();
        return view('frontend.checkout',compact('cart'));
    }

    public function checkout(Request $request){

        try{

            $user = Auth::user();

            if($user->carts()->count() == 0){
                toast("قم بأضافة منتجات الي السلة أولا",'warning');
                return redirect()->route('home');
            }

            if ($request->payment_option != null) {

                if($user->user_type == 'customer' || $user->user_type == 'designer'){
                    $code_z = Order::withoutGlobalScope('completed')->where('order_type','customer')->latest()->first()->order_num ?? 0;
                }elseif($user->user_type == 'seller'){
                    $code_z = Order::withoutGlobalScope('completed')->where('order_type','seller')->latest()->first()->order_num ?? 0;
                }else{
                    $code_z = 0;
                }
                $last_order_code = intval(str_replace('#','',strrchr($code_z,"#")));


                $order = new Order;
                $country = Country::findOrFail($request->shipping_country_id);

                $order->user_id  = $user->id;
                $order->shipping_country_id  = $country->id;
                $order->shipping_country_name  = $country->name;
                $order->shipping_country_cost  = $country->cost;
                $order->phone_number  = $request->phone_number;
                $order->phone_number2  = $request->phone_number2;
                $order->shipping_address = $request->shipping_address;
                $order->payment_type = $request->payment_option;

                if($user->user_type == 'seller'){
                    $order->client_name = $request->client_name;
                    $order->date_of_receiving_order = strtotime($request->date_of_receiving_order);
                    $order->excepected_deliverd_date = strtotime($request->excepected_deliverd_date);
                    $order->deposit = $request->deposit;
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

                $order->save();
                if($user->user_type == 'customer' || $user->user_type == 'designer'){
                    $order->order_num = 'customer#' . ($last_order_code + 1);
                }elseif($user->user_type == 'seller'){
                    $order->order_num = 'seller#' . ($last_order_code + 1);
                }

                $total_commission = 0;
                $total_cost = 0;
                $order_items = [];

                foreach($user->carts as $cartItem){
                    $product = Product::find($cartItem->product_id);
                    $product->num_of_sale += $cartItem->quantity;
                    $product->save();

                    if($product->variant_product == 1 && $cartItem->variation != null){
                        //remove requested quantity from stock
                        $product_stock = $product->stocks()->where('variant', $cartItem->variation)->first();
                        if($product_stock){
                            $product_stock->quantity -= $cartItem->quantity;
                            $product_stock->save();
                        }

                    }else {
                        //remove requested quantity from stock
                        $product->current_stock -= $cartItem->quantity;
                        $product->save();
                    }

                    //add commission to seller
                    $total_commission += $cartItem->commission;
                    $total_cost += $cartItem->total_cost;
                    $order_items [] = [
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'variation' => $cartItem->variation,
                        'link' => $cartItem->link,
                        'description' => $cartItem->description,
                        'commission' => $cartItem->commission,
                        'email_sent' => $cartItem->email_sent,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price,
                        'total_cost' => $cartItem->total_cost,
                        'photos' => $cartItem->photos,
                        'photos_note' => $cartItem->photos_note,
                        'pdf' => $cartItem->pdf,
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
                        $order->social_user_id = $seller->user_id;
                    }
                }
                $order->commission = $total_commission;
                $order->total_cost = $total_cost;
                $order->save();


                if($user->user_type == 'seller'){
                    $seller = Seller::where('user_id',$order->user_id)->first();
                    $seller->order_in_website += 1 ;
                    $seller->save();
                }

                if($request->payment_option == 'cash_on_delivery'){
                    if($this->checkout_done($order->id,'unpaid')){
                        toast("Your order has been placed successfully",'success');
                        return redirect()->route('frontend.orders.success',$order->id);
                    }
                }elseif($request->payment_option == 'paymob'){
                    $paymob = new PayMobController;
                    return $paymob->checkingOut('1602333','242734',$order->id,$request->first_name,$request->last_name,$request->phone_number);
                }
            }else {
                toast("Try Again",'error');
                return redirect()->route('home');
            }
        }catch (\Exception $ex){
            return $ex;
            toast("SomeThing Went Wrong!",'error');
            return redirect()->route('home');
        }
    }


    //redirects to this method after a successfull checkout
    public function checkout_done($order_id, $payment)
    {
        $user = Auth::user();

        $order = Order::withoutGlobalScope('completed')->find($order_id);
        $order->payment_status = $payment;
        $order->completed = 1;
        if($payment == 'paid'){
            $order->deposit_amount = $order->required_to_pay + $order->extra_commission + $order->shipping_country_cost;
        }
        $order->save();

        Cart::where('user_id',$user->id)->delete();

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
