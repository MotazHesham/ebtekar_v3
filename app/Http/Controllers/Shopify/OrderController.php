<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use App\Models\ReceiptCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function createOrUpdate(Request $request)
    {  
        $logger = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/shopify.log'),
            'level' => 'debug',
        ]);
        $logger->debug('shopify:', $request->toArray());
        
        try { 
            $site_settings = get_site_setting();

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
            $subtotal = $request->current_subtotal_price;
            $total = $request->current_total_price;

            $customer_name = $shipping_address['name']; 
            $customer_phone = $shipping_address['phone']; 
            $customer_address = $shipping_address['address1'] . ', ' . $shipping_address['address2'] . ', ' . $shipping_address['city'] . ', ' . $shipping_address['province'] . ', ' . $shipping_address['country'] . ', ' . $shipping_address['zip'];


            $description = '';
            foreach($products as $product) {
                $description .= "<p>Product: " . $product['name'] . "<br>";
                $description .= "Price: " . $product['price'] . "<br>";
                $description .= "Quantity: " . $product['quantity'] . "<br><br></p>";
            }

            $receiptCompany = ReceiptCompany::where('shopify_id', $shopify_id)->first();
            if(!$receiptCompany){
                $receiptCompany = new ReceiptCompany();
                $receiptCompany->shopify_id = $shopify_id;
                $receiptCompany->shopify_order_num = $shopify_order_num;
            } 
            $receiptCompany->client_name = $customer_name;
            $receiptCompany->client_type = 'individual';
            $receiptCompany->phone_number = $customer_phone;  
            $receiptCompany->total_cost = $total; 
            $receiptCompany->shipping_country_cost = $shipping_cost;
            $receiptCompany->shipping_address = $customer_address; 
            $receiptCompany->description = $description;
            $receiptCompany->save();
            $logger->debug('shopify:', ['success' => 'Success Shopify WebHook Order Created']); 
            return response()->json(null,200);
        } catch (\Exception $e) {
            $logger->debug('shopify:', ['error' => 'Error Dispatch Shopify Order: ' . $e]); 
            return response()->json(['error' => 'Error Dispatch Shopify Order: ' . $e], 422);
        } 
    }
}
