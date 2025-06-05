<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use App\Models\ReceiptCompany;
use App\Models\ReceiptSocial;
use App\Models\ReceiptSocialProduct;
use App\Models\ReceiptSocialProductPivot;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function createOrUpdate(Request $request, $website_setting_id)
    {  
        $logger = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/shopify.log'),
            'level' => 'debug',
        ]);
        $logger->debug('shopify:', $request->toArray());
        
        try { 
            $site_settings = WebsiteSetting::findOrFail($website_setting_id);

            // Validation
                $hmac = request()->header('x-shopify-hmac-sha256');
                $shop_domain = request()->header('x-shopify-shop-domain');
                $data = request()->getContent();
                $webhookSecret = $site_settings->shopify_webhook_sign;

                $hmacLocal = base64_encode(hash_hmac('sha256', $data, $webhookSecret, true));
                if (!hash_equals($hmac, $hmacLocal)) {
                    // Issue with HMAC or missing shop header
                    $logger->debug('shopify:', ['error' => 'Invalid webhook signature']);
                    return response()->json(['error' => 'Invalid webhook signature'], 422);
                }
            // ---------- 

            if(!$site_settings->shopify_integration_status){
                $logger->debug('shopify:', ['error' => 'Shopify Integration is not enabled']);
                return response()->json(['error' => 'Shopify Integration is not enabled'], 422);
            }
            
            $shopify_id = $request->admin_graphql_api_id;
            $shopify_order_num = $request->order_number;
            $products = $request->line_items;
            $shipping_address = $request->shipping_address; 
            $shipping_cost= $request->current_shipping_price_set['shop_money']['amount']; 
            $total = $request->current_total_price;

            $customer_name = $shipping_address['name']; 
            $customer_phone = $shipping_address['phone']; 
            $customer_address = $shipping_address['address1'] . ', ' . $shipping_address['address2'] . ', ' . $shipping_address['city'] . ', ' . $shipping_address['province'] . ', ' . $shipping_address['country'] . ', ' . $shipping_address['zip'];



            $receiptSocial = ReceiptSocial::where('shopify_id', $shopify_id)->first();
            if(!$receiptSocial){
                $receiptSocial = new ReceiptSocial();
                $receiptSocial->shopify_id = $shopify_id;
                $receiptSocial->shopify_order_num = $shopify_order_num;
                $receiptSocial->website_setting_id = $site_settings->id;
            } 
            $receiptSocial->client_name = $customer_name;
            $receiptSocial->client_type = 'individual';
            $receiptSocial->phone_number = $customer_phone;  
            $receiptSocial->total_cost = $total - $shipping_cost; 
            $receiptSocial->shipping_country_cost = $shipping_cost;
            $receiptSocial->shipping_address = $customer_address;  
            $receiptSocial->save();
            
            foreach($products as $product) {
                $receiptSocialProduct = ReceiptSocialProduct::updateOrCreate(
                    [
                        'website_setting_id' => $site_settings->id,
                        'shopify_id' => $product['product_id'], 
                        'name' => $product['name'],
                    ],
                    [
                        'price' => $product['price'], 
                    ]
                );
                $itemProperties = $product['properties'];
                $propertiesString = '';
                if (!empty($itemProperties)) {
                    $propertiesArray = [];
                    foreach ($itemProperties as $property) {
                        $propertiesArray[] = $property['name'] . ': ' . $property['value'];
                    }
                    $propertiesString = implode(' | ', $propertiesArray);
                }

                ReceiptSocialProductPivot::updateOrCreate(
                    [
                        'shopify_id' => $product['admin_graphql_api_id'],
                    ],
                    [
                        'receipt_social_id' => $receiptSocial->id,
                        'receipt_social_product_id' => $receiptSocialProduct->id,
                        'title' => $product['name'],
                        'description' => $propertiesString,
                        'quantity' => $product['quantity'],
                        'price' => $product['price'],
                        'total_cost' => $product['price'] * $product['quantity'],
                    ]
                );
            }
            $logger->debug('shopify:', ['success' => 'Success Shopify WebHook Order Created']); 
            return response()->json(null,200);
        } catch (\Exception $e) {
            $logger->debug('shopify:', ['error' => 'Error Dispatch Shopify Order: ' . $e]); 
            return response()->json(['error' => 'Error Dispatch Shopify Order: ' . $e], 422);
        } 
    }
}
