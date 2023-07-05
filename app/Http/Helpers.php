<?php

//returns combinations of customer choice options array

use App\Models\Currency; 
use App\Models\Order;
use App\Models\ReceiptClient;
use App\Models\ReceiptCompany;
use App\Models\ReceiptSocial;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Session;
use Stevebauman\Location\Facades\Location;

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

if (!function_exists('product_price_in_cart')) {
    function product_price_in_cart($quantity,$variation,$product)
    {
        $product_stock = \App\Models\ProductStock::where('variant', $variation)->first();
        if($product_stock){
            $price_before_discount = front_calc_product_currency($product_stock->unit_price,$product->weight);
            $price = front_calc_product_currency($product->calc_discount($product_stock->unit_price),$product->weight);
            $commission = ($product_stock->unit_price  - $product_stock->purchase_price) * $quantity; 
        }else{
            $price_before_discount = front_calc_product_currency($product->unit_price,$product->weight);
            $price = front_calc_product_currency($product->calc_discount($product->unit_price),$product->weight); 
            $commission = ($product->unit_price  - $product->purchase_price) * $quantity;
        } 
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
}
