<?php

//returns combinations of customer choice options array

use App\Models\Order;
use App\Models\ReceiptClient;
use App\Models\ReceiptCompany;
use App\Models\ReceiptSocial;

if (!function_exists('dashboard_currency')) {
    function dashboard_currency($value)
    {
        return $value . ' EGP';
    }
}

if (!function_exists('front_currency')) {
    function front_currency($value)
    {
        return 'EGP ' . $value;
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
