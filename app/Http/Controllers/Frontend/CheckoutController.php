<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PayMobController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Requests\Frontend\CheckoutOrder;
use App\Jobs\SendFacebookEventJob;
use App\Jobs\SendOrderConfirmationMail;
use App\Jobs\SendOrderConfirmationSMS;
use App\Jobs\SendPushNotification;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Customer;
use App\Models\DeviceUserToken;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserAlert;
use App\Services\FacebookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Nafezly\Payments\Classes\PaymobWalletPayment;
use Nafezly\Payments\Classes\PaymobPayment;
class CheckoutController extends Controller
{
    public function checkout_summary(Request $request){
        $discount_code = $request->discount_code;
        $wrong_disocunt_code = false; 
        $total_cost = 0;  
        $discount = 0;
        $shipping = 0;

        if(session('cart')){
            foreach(session('cart') as $cartItem){
                $product = Product::find($cartItem['product_id']); 
                if($product){
                    $prices = product_price_in_cart($cartItem['quantity'],$cartItem['variation'],$product);
                    $total_cost += ($prices['price']['value'] * $cartItem['quantity'] );
                    $symbol = $prices['price']['symbol'];
                } 
            }  
        }
        
        if($request->discount_code != null){
            $seller = Seller::where('discount_code',$request->discount_code)->first();
            if(!$seller || $seller->discount < 0){  
                $wrong_disocunt_code = true;
            }else{
                $discount = $total_cost * ($seller->discount / 100);  
            }
        }
        
        if($request->country_id != null){ 
            $country = Country::find($request->country_id);
            $shipping  = $country->cost ?? 0;
        } 

        return view('frontend.partials.summary',compact('shipping','discount','discount_code','wrong_disocunt_code'));
    }

    public function payment_select(){ 
        $site_settings = get_site_setting();
        validateCart();
        
        if(!session('cart') || count(session('cart')) < 1){
            alert("قم بأضافة منتجات الي السلة أولا",'','warning');
            return redirect()->route('home');
        }

        $eventData  = null;
        if($site_settings->fb_pixel_id){ 
            $price = 0;
            $numOfItems = 0;
            $productsIds = [];
            foreach (session('cart') as $key => $cartItem){
                $product = Product::find($cartItem['product_id']); 
                $price += $product->unit_price;
                $productsIds[] = (string) $product->id;
                $numOfItems = $cartItem['quantity'];
            }
    
            $eventData = [
                'event' => 'InitiateCheckout',
                'content_ids' => $productsIds,
                'content_type' => 'product', 
                'value' => (float)$price,
                'currency' => 'EGP', 
                'num_items' => (int) $numOfItems
            ];

            $userData = getUserDataForConersionApi();
            SendFacebookEventJob::dispatch($eventData, $site_settings->id,$userData,'all');   
        }
        $countries = Country::where('status',1)->where('website',1)->get()->groupBy('type'); 
        $currency_symbol =  session("currency")->symbol ?? 'EGP';
        return view('frontend.checkout',compact('countries','currency_symbol','eventData'));
    } 

    public function checkout(CheckoutOrder $request){  
        try{
            DB::beginTransaction();
            $site_settings = get_site_setting();
            
            if(!session('cart') || count(session('cart')) < 1){
                alert("قم بأضافة منتجات الي السلة أولا",'','warning');
                return redirect()->route('home');
            } 
            
            $validatedCart = validateCart(); // check for avaialbilty for product
            if($validatedCart['return']){ 
                alert($validatedCart['alert_text'],'','warning');
                return redirect()->route($validatedCart['route']);
            }


            if($request->discount_code != null){
                $seller = Seller::where('discount_code',$request->discount_code)->first();
                if(!$seller || $seller->discount < 0){ 
                    alert("خطأ في كود الخصم",'','warning');
                    return redirect()->route('frontend.payment_select');
                }
            }

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
            
            if ($request->payment_option != null) {

                if($user && $user->user_type == 'seller'){
                    $code_z = Order::withoutGlobalScope('completed')->where('order_type','seller')->where('website_setting_id',$site_settings->id)->latest()->first()->order_num ?? 0;
                }else{
                    $code_z = Order::withoutGlobalScope('completed')->where('order_type','customer')->where('website_setting_id',$site_settings->id)->latest()->first()->order_num ?? 0;
                }
                $last_order_code = intval(str_replace('#','',strrchr($code_z,"#")));


                $order = new Order;
                $country = Country::findOrFail($request->country_id);
                $currency = session('currency');
                
                $order->user_id  = $user->id ?? null;
                $order->shipping_country_id  = $country->id; 
                $order->website_setting_id  = $site_settings->id; 
                $order->shipping_country_cost  = $country->cost;
                $order->phone_number  = $request->phone_number;
                $order->phone_number_2  = $request->phone_number_2;
                $order->shipping_address = $request->shipping_address;
                $order->payment_type = $request->payment_option;
                $order->symbol = $currency->symbol;
                $order->exchange_rate = $currency->exchange_rate;

                if($site_settings->fb_pixel_id){
                    $str = 'ertgal-';
                }elseif($site_settings->id == 3){
                    $str = 'figures-';
                }elseif($site_settings->id == 4){
                    $str = 'shirti-';
                }else{ 
                    $str = 'ebtekar-';
                } 

                if($user && $user->user_type == 'seller'){
                    $order->client_name = $request->first_name . ' ' . $request->last_name;
                    $order->date_of_receiving_order = $request->date_of_receiving_order;
                    $order->excepected_deliverd_date = $request->excepected_deliverd_date;
                    $order->deposit_type = $request->deposit_type;
                    $order->deposit_amount = $request->deposit_amount;
                    $order->free_shipping = $request->free_shipping;
                    $order->shipping_cost_by_seller = $request->shipping_cost_by_seller;
                    $order->free_shipping_reason = $request->free_shipping_reason;
                    $order->total_cost_by_seller = $request->total_cost_by_seller;
                    $order->order_type = 'seller';
                    $order->order_num = $str . 'seller#' . ($last_order_code + 1);
                }else{
                    $order->client_name = $request->first_name . ' ' . $request->last_name;
                    $order->order_type = 'customer';
                    $order->order_num = $str . 'customer#' . ($last_order_code + 1);
                }


                $order->save();

                $total_commission = 0;
                $total_cost = 0;
                $order_items = [];
                $numOfItems = 0;
                $productsIds = []; 

                foreach(session('cart') as $cartItem){ 
                    $numOfItems += $cartItem['quantity'];
                    $productsIds[] = (string) $cartItem['product_id'];

                    // increse number of sales in product
                    $product = Product::findOrFail($cartItem['product_id']);
                    $product->num_of_sale += $cartItem['quantity'];
                    $product->save();
                    $weight = $product->weight;

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

                    $prices = calc_product_cost($product,$cartItem['variation']);
                    $calc_total_for_product= ($prices['price'] + $currency->$weight) * $cartItem['quantity']  ;
                    $total_cost += $calc_total_for_product;

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
                        'price' => $prices['price'],
                        'weight_price' =>  $currency->$weight,
                        'total_cost' => $calc_total_for_product,
                        'photos' => $cartItem['photos'] ?? null, 
                        'pdf' => $cartItem['pdf'] ?? null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }

                OrderDetail::insert($order_items);



                if($request->discount_code != null){
                    $seller = Seller::where('discount_code',$request->discount_code)->first();
                    if($seller && $seller->discount > 0){
                        $discount_cost = $total_cost * ($seller->discount / 100); 
                        $order->discount = $discount_cost;
                        $order->discount_code = $request->discount_code;
                        $order->social_user_id = $seller->user_id; // it stands for the owner of the discount code
                    }else{  
                        throw ValidationException::withMessages(['discount_code' => 'Discount Code Faild']);
                    }
                }
                $order->commission = $total_commission;
                $order->total_cost = $total_cost; 
                $order->save();


                if($user && $user->user_type == 'seller'){
                    $seller = Seller::where('user_id',$order->user_id)->first();
                    $seller->order_in_website += 1 ;
                    $seller->save();
                }

                $currenct_exchange_rate = Cache::get('currency_rates')[$order->symbol];
                
                if($site_settings->fb_pixel_id){ 
            
                    $eventData = [
                        'event' => 'Purchase',
                        'content_ids' => $productsIds,
                        'content_type' => 'product', 
                        'value' => (float)$total_cost,
                        'currency' => 'EGP', 
                        'num_items' => (int) $numOfItems
                    ]; 
                    $countryCode = null;
                    $city = null;
                    if($country->type == 'cities'){
                        $countryCode = $country->code;
                    }else{
                        $countryCode = 'EG';
                        $city =  transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $country->name);
                    }
                    if($user){
                        $userData = getUserDataForConersionApi($user,[
                            'countryCode' => $countryCode,
                            'city' => $city,
                        ]);
                    }else{
                        $userData = getUserDataForConersionApi(null,[
                            'external_id' => rand(0,9999),
                            'email' => $request->email,
                            'phone' => $request->phone_number,
                            'firstName' => $request->first_name,
                            'lastName' => $request->last_name,
                            'countryCode' => $countryCode,
                            'city' => $city,
                        ]); 
                    }
                    SendFacebookEventJob::dispatch($eventData, $site_settings->id,$userData,'all'); 
                }

                if($request->payment_option == 'cash_on_delivery'){
                    if($this->checkout_done($order->id,'unpaid')){
                        DB::commit();
                        toast("Your order has been placed successfully",'success');
                        if($request->has('create_account')){
                            Auth::login($user);
                        } 
                        return redirect()->route('frontend.orders.success',$order->id);
                    }
                }elseif($request->payment_option == 'paymob'){ 
                    DB::commit(); 
                    $paymobPayment = new PaymobPayment();
                    //pay function 
                    $response = $paymobPayment->pay(
                        ($order->calc_total_for_client()/$order->exchange_rate) * $currenct_exchange_rate, 
                        $user_id = $user->id ?? null, 
                        $user_first_name = $request->first_name, 
                        $user_last_name = $request->last_name, 
                        $user_email = $user->email ?? $request->first_name . '@temp.test', 
                        $user_phone = $request->phone_number, 
                        $source = null
                    ); 
                    $order->update(['paymob_orderid' => $response['payment_id']]); // save paymob order id for later usage. 
                    if($response['redirect_url']){
                        return redirect($response['redirect_url']);
                    }else{ 
                        alert('لم تتم العملية','حدث خطأ حاول لاحقا','warning');
                        return redirect()->route('home');
                    }
                }elseif($request->payment_option == 'wallet'){
                    DB::commit(); 
                    $paymobwalletpayment = new PaymobWalletPayment();
                    //pay function
                    $response = $paymobwalletpayment->pay(
                        ($order->calc_total_for_client()/$order->exchange_rate) * $currenct_exchange_rate, 
                        $user_id = $user->id ?? null, 
                        $user_first_name = $request->first_name, 
                        $user_last_name = $request->last_name, 
                        $user_email = $user->email ?? $request->first_name . '@temp.test', 
                        $user_phone = $request->phone_number, 
                        $source = null
                    ); 
                    $order->update(['paymob_orderid' => $response['payment_id']]); // save paymob order id for later usage. 
                    if($response['redirect_url']){
                        return redirect($response['redirect_url']);
                    }else{ 
                        alert("لم تتم العملية",'ربما رقم المحفظة الذى ادخلته غير صحيح برجاء ادخال رقم صحيح واعاده طلب الاوردر','warning');
                        return redirect()->route('home');
                    }
                }
            }else {
                toast("Try Again",'error');
                return redirect()->route('home');
            }
        }catch (\Exception $ex){ 
            DB::rollBack(); 
            toast("SomeThing Went Wrong!",'error');
            return redirect()->route('home');
        }
    }


    //redirects to this method after a successfull checkout
    public function checkout_done($order_id, $payment)
    { 
        $order = Order::withoutGlobalScope('completed')->with('orderDetails.product')->find($order_id);
        $order->payment_status = $payment;
        $order->completed = 1;
        if($payment == 'paid'){
            $order->deposit_amount = $order->calc_total() - $order->calc_discount();
        }
        $order->save();

        session()->put('cart',null);

        $site_settings = get_site_setting();

        //create the notification that will send to the admin
        $title = $order->client_name . ' - ' .  $order->phone_number;
        $body = $order->order_num .' طلب جديد من الموقع';
        $userAlert = UserAlert::create([
            'alert_text' => $body,
            'alert_link' => route('admin.orders.show', $order->id),
            'type' => 'orders', 
        ]); 

        // only users has permission order_show can see the notification
        $allowed_users_ids = User::where('user_type','staff')->whereHas('roles.permissions',function($query){
            $query->where('permissions.title','order_show');
        })->pluck('id')->all();
        $userAlert->users()->sync($allowed_users_ids); 

        // send push notification to users has the permission and has a device_token to send via firebase
        $ids = User::whereNotNull('device_token')->whereHas('roles.permissions',function($query){
            $query->where('permissions.title','order_show');
        })->where('user_type','staff')->pluck('id')->all();   
        $tokens = DeviceUserToken::whereIn('user_id',$ids)->pluck('device_token')->all();
        SendPushNotification::dispatch($title, $body, $tokens,route('admin.orders.show', $order->id),$site_settings);  // job for sending push notification

        // send the order confirmation to the user
        if($order->user){
            SendOrderConfirmationMail::dispatch($order,$site_settings,$order->user->email); // job for sending confirmation mail
        }

        if($order->phone_number){
            SendOrderConfirmationSMS::dispatch($order,$site_settings,$order->phone_number); // job for send whatsapp message
        }

        return 1;
    }
}
