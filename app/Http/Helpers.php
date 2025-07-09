<?php

//returns combinations of customer choice options array

use App\Models\Country;
use App\Models\Currency; 
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ReceiptClient;
use App\Models\ReceiptCompany;
use App\Models\ReceiptSocial;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Str;

if (!function_exists('validateCart')) {
    function validateCart()
    {

        $cart = session()->get('cart', []); // Retrieve cart from session 
        if($cart){
            if(count($cart) > 0){
                foreach ($cart as $item) {
                    $product = Product::find($item['product_id']); 

                    if(!$product){
                        $cart = $cart->where('id','!=',$item['id']);
                        session()->put('cart',$cart);  
                        $alert_text = "منتج غير متوفر";
                        $route = 'frontend.payment_select';
                        $return = true;
                    }

                    $available_quantity = $product->current_stock;
                    if($product->variant_product == 1 && $item['variation'] != null){ 
                        $product_stock = $product->stocks()->where('variant', $item['variation'])->first();
                        if($product_stock){
                            $available_quantity = $product_stock->stock;
                        }
                    }

                    if (!$product->published || $available_quantity < $item['quantity']) {
                        $cart = $cart->where('id','!=',$item['id']);
                        session()->put('cart',$cart);  
                        $alert_text = "عذرا المنتج " . $product->name . " غير متوفر حاليا";
                        $route = 'frontend.payment_select';
                        $return = true;
                    }
                }
            } 
        }
        return [ 
            'alert_text' => $alert_text ?? '',
            'return' => $return ?? false,
            'route' => $route ?? 'home',
        ];
    }
}

if (!function_exists('get_site_setting')) {
    function get_site_setting()
    {
        return WebsiteSetting::where('domains','like','%' . request()->getHost() . '%')->first() ?? WebsiteSetting::first(); 
    }
} 

if (!function_exists('dashboard_currency')) {
    function dashboard_currency($value)
    {
        return $value . ' EGP';
    }
}

if (!function_exists('getCountryNameById')) {
    function getCountryNameById($id)
    {
        $countries = Cache::remember('countries', 60*24, function() {
            return Country::pluck('name', 'id');
        });
        return $countries[$id] ?? '';
    }
}

if (!function_exists('setCurrencyRate')) {
    function setCurrencyRate()
    {
        if(app()->isProduction()){
            Cache::remember('currency_rates', 21600, function () {  // 6 hours
                $response = Http::get('https://api.currencyfreaks.com/v2.0/rates/latest?apikey='.config('app.currencyfreaks_api_key').'&symbols=EGP,SAR,KWD,AED'); 
                if ($response->successful()) {
                    $jsonData = $response->json(); 
        
                    // Extract the base currency and rates
                    $baseCurrency = $jsonData['base'];
                    $rates = $jsonData['rates'];
        
                    // Convert the base currency to EGP
                    if ($baseCurrency !== 'EGP') {
                        $newRates = [];
                        foreach ($rates as $currency => $rate) {
                            $newRates[$currency] =  round($rates['EGP'] / $rate,2);
                        }
                        $rates = $newRates;
                    }
        
                    return $rates;
        
                } else { 
                    return [
                        'AED' => 13,
                        'SAR' => 13,
                        'KWD' => 160,
                        'EGP' => 1
                    ];
                }
            });    
                
        }else{
            Cache::remember('currency_rates', 21600, function () {  // 6 hours
                return [
                    'AED' => 13,
                    'SAR' => 13,
                    'KWD' => 160,
                    'EGP' => 1
                ];
            });    
        }
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ=#%$@&';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
if (! function_exists('calculate_commission')) {
    function calculate_commission($orders) {
        $pending = 0 ;
        $available = 0 ;
        $requested = 0 ;
        $delivered = 0 ;

        foreach($orders as $order){
            if($order->delivery_status != 'cancel'){

                if($order->commission_status == 'pending'){
                    if($order->delivery_status == 'delivered'){
                        $available += $order->commission + $order->extra_commission;
                    }else{
                        $pending += $order->commission + $order->extra_commission;
                    }
                }else if($order->commission_status == 'requested'){
                    $requested += $order->commission + $order->extra_commission;
                }else if($order->commission_status == 'delivered'){
                    $delivered += $order->commission + $order->extra_commission;
                }

            }
        }

        $data = [
            'pending' => $pending . 'EGP',
            'available' => $available . 'EGP',
            'requested' => $requested . 'EGP',
            'delivered' => $delivered . 'EGP',
        ];

        return $data;
    }
}
if (! function_exists('designs_calculations')) {
    function designs_calculations($designs) {
        $pending = 0 ;
        $available = 0 ; 


        foreach($designs as $design){ 
            $single_desing_calculations = single_design_calcualtions($design);
            $pending += $single_desing_calculations['pending'];
            $available += $single_desing_calculations['available'];
        }

        $data = [
            'pending' => $pending,
            'available' => $available, 
        ];

        return $data;
    }
}
if (! function_exists('single_design_calcualtions')) {
    function single_design_calcualtions($design) { 
        $pending = 0;
        $pending_quantity = 0;
        $available = 0; 
        $available_quantity = 0; 

        $product = Product::where('design_id',$design->id)->first();
        if($product){
            $order_details = OrderDetail::with('order')->where('product_id',$product->id)->get();

            foreach($order_details as $raw){
                if($raw->order->delivery_status != 'cancel'){
                    if($raw->order->delivery_status == 'delivered'){
                        $available += $raw->quantity * $design->profit;  
                        $available_quantity += $raw->quantity;  
                    }else{
                        $pending += $raw->quantity * $design->profit;  
                        $pending_quantity += $raw->quantity;  
                    }
                }
            }
        } 
        $data = [
            'pending' => $pending,
            'available' => $available, 
            'pending_quantity' => $pending_quantity, 
            'available_quantity' => $available_quantity, 
        ];

        return $data;
    }
}


if (!function_exists('calc_product_cost')) {
    function calc_product_cost($product, $variation){

        $product_stock = \App\Models\ProductStock::where('variant', $variation)->where('product_id',$product->id)->first(); 

        $unit_price =  $product_stock ? $product_stock->unit_price : $product->unit_price;
        $purchase_price = $product_stock ? $product_stock->purchase_price : $product->purchase_price;  
        
        return [
            'price_before_discount' => $unit_price,
            'price' => $product->calc_discount($unit_price),
            'commission' => $unit_price - $purchase_price,
        ];
    }
}

if (!function_exists('product_price_in_cart')) {
    function product_price_in_cart($quantity,$variation,$product)
    {
        $product_stock = \App\Models\ProductStock::where('variant', $variation)->where('product_id',$product->id)->first(); 

        $unit_price =  $product_stock ? $product_stock->unit_price : $product->unit_price;
        $purchase_price = $product_stock ? $product_stock->purchase_price : $product->purchase_price; 
        
        $price_before_discount = front_calc_product_currency($unit_price,$product->weight);
        $price = front_calc_product_currency($product->calc_discount($unit_price),$product->weight); 
        $commission = front_calc_commission_currency($unit_price, $purchase_price)['value'] * $quantity;

        $h2 = '';
        
        if($product->discount > 0){
            $h2 .= $price['as_text'] ;
            $h2 .= ' <span style="text-decoration:line-through">' . $price_before_discount['as_text'] . '</span>';
        }else{
            $h2 .= $price['as_text'];
        } 
        
        return [ 
            'commission' => $commission,
            'price' => $price,
            'price_before_discount' => $price_before_discount,
            'h2' => $h2,
        ];
    }
}

if (!function_exists('front_calc_commission_currency')) {
    function front_calc_commission_currency($unit_price,$purchase_price){
        $currency = session('currency');
        if($currency){
            $product_unit_price = exchange_rate($unit_price,$currency->exchange_rate); 
            $product_purchase_price = exchange_rate($purchase_price,$currency->exchange_rate); 
            $commission = $product_unit_price - $product_purchase_price; 
            return [
                'as_text' => $currency->symbol . ' ' . $commission,
                'value' => $commission,
                'symbol' =>  ' ' . $currency->symbol,
            ];
        }else{
            return [
                'as_text' => 'EGP ' . ($unit_price - $purchase_price),
                'value' => ($unit_price - $purchase_price),
                'symbol' => 'EGP '
            ];
        } 
    }
}
if (!function_exists('front_calc_product_currency')) {
    function front_calc_product_currency($value,$weight)
    {
        $currency = session('currency');
        if($currency){
            $product_price = exchange_rate($value,$currency->exchange_rate);
            $product_weight = exchange_rate($currency->$weight,$currency->exchange_rate);
            $price = $product_price + $product_weight; 
            return [
                'as_text' => $currency->symbol . ' ' . $price,
                'value' => $price,
                'symbol' =>  ' ' . $currency->symbol,
            ];
        }else{
            return [
                'as_text' => 'EGP ' . $value,
                'value' => $value,
                'symbol' => 'EGP '
            ];
        } 
    }
}    

if (!function_exists('exchange_rate')) {
    function exchange_rate($value,$exchange_rate){
        if($exchange_rate == 0){
            $exchange_rate = 1;
        }
        return round($value / $exchange_rate,2); 
    }
} 

if (!function_exists('get_currency_info')) {
    function get_currency_info($value,$weight)
    {
        $country_code = Session::get('country_code') ?? 'EG'; 
        $currency = Currency::where('code',$country_code)->first();
        if($currency){ 
            $price = ($value / $currency->exchange_rate) + $currency->$weight;
            return [ 
                'price' => round($price),
                'exchange_rate' => $currency->exchange_rate,
                'weight_price' => $currency->$weight,
                'symbol' => $currency->symbol,
            ];
        }else{ 
            return [ 
                'price' => round($value),
                'weight_price' => 0,
                'exchange_rate' => 1,
                'symbol' => 'EGP',
            ];
        } 
    }
}   

if (!function_exists('getWebsiteSettingPrefix')) {
    function getWebsiteSettingPrefix($id)
    {
        $website_setting = WebsiteSetting::find($id);
        if($website_setting){
            return $website_setting->order_num_prefix . '-';
        }else{
            return 'ebtekar-';
        } 
    }
}

if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber($type,$website_setting_id = null)
    {
        $prefix = $website_setting_id ? getWebsiteSettingPrefix($website_setting_id) : null;
        return DB::transaction(function () use ($type,$prefix) {
            // This locks only the selected row(s) matching the where condition
            // lockForUpdate() acquires a row-level lock on the rows that match the query
            $counter = DB::table('order_number_counters')
                ->where('type', $type)
                ->where('prefix', $prefix)
                ->lockForUpdate()
                ->first();

            if (!$counter) { 
                DB::table('order_number_counters')->insert([
                    'type' => $type,
                    'last_number' => 1,
                ]);
                $newNumber = 1;
            } else {
                $newNumber = $counter->last_number + 1;
                DB::table('order_number_counters')
                    ->where('type', $type)
                    ->where('prefix', $prefix)
                    ->update(['last_number' => $newNumber]);
            }

            return $prefix . $type . $newNumber;
        });
    }
}

if (!function_exists('combinations')) {
    function combinations($arrays)
    {
        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }
} 

// search by phone number
if (!function_exists('searchByPhone')) {
    function searchByPhone($num)
    {
        global $phone;
        $phone = $num;

        $raw = ReceiptSocial::where(function ($query) {
            $query->where('phone_number', 'like', '%' . $GLOBALS['phone'] . '%')->orWhere('phone_number_2', 'like', '%' . $GLOBALS['phone'] . '%');
        })->first();

        if (!$raw) {
            $raw = ReceiptClient::where('phone_number', 'like', '%' . $phone . '%')->first();
        } elseif (!$raw) {
            $raw = ReceiptCompany::where(function ($query) {
                $query->where('phone_number', 'like', '%' . $GLOBALS['phone'] . '%')->orWhere('phone_number_2', 'like', '%' . $GLOBALS['phone'] . '%');
            })
                ->orderBy('created_at', 'desc')
                ->first();
        } elseif (!$raw) {
            $raw = Order::where('order_type', 'customer')
                ->where(function ($query) {
                    $query->where('phone_number', 'like', '%' . $GLOBALS['phone'] . '%')->orWhere('phone_number_2', 'like', '%' . $GLOBALS['phone'] . '%');
                })
                ->orderBy('created_at', 'desc')
                ->first();
        } elseif (!$raw) {
            $raw = Order::where('order_type', 'seller')
                ->where(function ($query) {
                    $query->where('phone_number', 'like', '%' . $GLOBALS['phone'] . '%')->orWhere('phone_number_2', 'like', '%' . $GLOBALS['phone'] . '%');
                })
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return [
            'client_name' => isset($raw->client_name) ? $raw->client_name :  null,
            'client_type' => isset($raw->client_type) ? $raw->client_type : null,
            'phone_number' => isset($raw->phone_number) ? $raw->phone_number : null,
            'phone_number_2' => isset($raw->phone_number_2) ? $raw->phone_number_2 : null,
            'shipping_address' => isset($raw->shipping_address) ? $raw->shipping_address : null,
            'shipping_country_id' => isset($raw->shipping_country_id) ? $raw->shipping_country_id : null,
        ];
    }

    if (!function_exists('getFbp')) {
        function getFbp()
        {
            // 1. Try to get existing cookie first
            if ($fbp = request()->cookie('_fbp')) {
                return $fbp;
            }
            

            // 2. Generate new FBP if doesn't exist
            $fbp = 'fb.1.' . getSafeEventTime() . '.' . bin2hex(random_bytes(6)); // More reliable than uniqid()
            
            // Set cookie with proper attributes
            cookie()->queue(
                name: '_fbp',
                value: $fbp,
                minutes: 60 * 24 * 90, // 90 days
                secure: true,           // Required for HTTPS
                httpOnly: true,         // Better security
                sameSite: 'Lax'         // Recommended for tracking cookies
            );
    
            return $fbp;
        }
    }
    if (!function_exists('getFbc')) {
        function getFbc()
        {
            // 1. Check for existing cookie first
            if ($fbc = request()->cookie('_fbc')) {
                return $fbc;
            }
            
            // 2. Check for fbclid parameter
            if ($fbclid = request()->input('fbclid')) { 
                
                $fbc = 'fb.1.' . getSafeEventTime() . '.' . $fbclid;
                
                // Set cookie with proper attributes
                cookie()->queue(
                    name: '_fbc',
                    value: $fbc,
                    minutes: 60 * 24 * 90, // 90 days
                    secure: true, // For HTTPS
                    httpOnly: true,
                    sameSite: 'Lax'
                );
                
                return $fbc;
            }
            
            return null;
        }
    }
    if (!function_exists('getUserDataForConersionApi')) {
        function getUserDataForConersionApi($user = null,$data = null)
        {  
            $userData = [
                'ip' => request()->ip(),
                'userAgent' => request()->userAgent(),
                'fbp' => getFbp(),
                'fbc' => getFbc(),
            ];

            if(auth()->check()){ 
                $user = User::find(auth()->id());
            }
            if($user){
                $userData['external_id'] =  getHashedExternalIdForCAPI();
                $userData['email'] =  $user->hashedEmail();
                $userData['phone'] =  $user->hashedPhone();
                $userData['firstName'] =  $user->hashedFirstName();
                $userData['lastName'] =  $user->hashedLastName(); 
            }elseif($data){
                $userData['external_id'] =  getHashedExternalIdForCAPI();
                $userData['email'] =  hashedForConversionApi($data['email']);
                $userData['phone'] =  hash('sha256', preg_replace('/\D/', '', $data['phone']));
                $userData['firstName'] =  hashedForConversionApi($data['firstName']);
                $userData['lastName'] =  hashedForConversionApi($data['lastName']); 
            }

            $userData['city'] = getHashedCityForCAPI();
            $userData['state'] = getHashedStateForCAPI();
            $userData['countryCode'] = getHashedCountryForCAPI();
            if($data){
                $userData['countryCode'] = $data['countryCode'] ? hashedForConversionApi($data['countryCode']) : $userData['countryCode'];
                $userData['city'] = $data['city'] ? hash('sha256',strtolower(trim($data['city']))) : $userData['city'];
            }
            
            return $userData;
        }
    }   
    if (!function_exists('hashedForConversionApi')) {
        function hashedForConversionApi($text = null)
        {  
            return $text ? hash("sha256",strtolower(trim( $text))) : null;
        }
    }
    if (!function_exists('getHashedStateForCAPI')) {
        function getHashedStateForCAPI()
        {  
            $state = Session::get('state_by_ip','Cairo'); 
            return $state ? hashedForConversionApi($state) : null; 
        }
    }
    if (!function_exists('getHashedCityForCAPI')) {
        function getHashedCityForCAPI()
        {  
            $city = Session::get('city_by_ip','Cairo'); 
            return $city ?  hashedForConversionApi($city) : null; 
        }
    }
    if (!function_exists('getHashedCountryForCAPI')) {
        function getHashedCountryForCAPI()
        {  
            $country = Session::get('country_code','EG'); 
            return $country ? hashedForConversionApi($country) : null; 
        }
    }
    if (!function_exists('getHashedExternalIdForCAPI')) {
        function getHashedExternalIdForCAPI()
        {  
            $external_id = auth()->check() ? auth()->id() : Session::get('external_id', 'rand_ext_' . Str::random(8));
    
            return $external_id ? hashedForConversionApi($external_id) : null;
        }
    }
    if (!function_exists('getSafeEventTime')) {
        function getSafeEventTime()
        {  
            return time() - 5;
        }
    }


}
