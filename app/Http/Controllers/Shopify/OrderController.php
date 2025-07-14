<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessShopifyOrderJob;
use App\Models\ReceiptSocial;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function createOrUpdate(Request $request, $website_setting_id)
    {
        $logger = Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/shopify.log'),
            'level' => 'debug',
        ]); 

        try {
            $site_settings = WebsiteSetting::findOrFail($website_setting_id);

            // Validation
            $hmac = request()->header('x-shopify-hmac-sha256'); 
            $data = request()->getContent();
            $webhookSecret = $site_settings->shopify_webhook_sign;

            $hmacLocal = base64_encode(hash_hmac('sha256', $data, $webhookSecret, true));
            if (!hash_equals($hmac, $hmacLocal)) {
                // Issue with HMAC or missing shop header
                $logger->debug('shopify:', ['error' => 'Invalid webhook signature']);
                return response()->json(['error' => 'Invalid webhook signature'], 422);
            }
            // ---------- 

            if (!$site_settings->shopify_integration_status) {
                $logger->debug('shopify:', ['error' => 'Shopify Integration is not enabled']);
                return response()->json(['error' => 'Shopify Integration is not enabled'], 422);
            }

            // Dispatch the job to process the order asynchronously
            ProcessShopifyOrderJob::dispatch($request->toArray(), $site_settings);

            $logger->debug('shopify:', ['success' => 'Shopify WebHook Order Job Dispatched ' . $request->order_number]);
            return response()->json(null, 200);
        } catch (\Exception $e) {
            $logger->debug('shopify:', ['error' => 'Error Dispatch Shopify Order: ' . $e]);
            return response()->json(['error' => 'Error Dispatch Shopify Order: ' . $e], 422);
        }
    }

    public function delete(Request $request, $website_setting_id)
    {
        $logger = Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/shopify.log'),
            'level' => 'debug',
        ]); 

        try {
            $site_settings = WebsiteSetting::findOrFail($website_setting_id);
            // Validation
            $hmac = request()->header('x-shopify-hmac-sha256'); 
            $data = request()->getContent();
            $webhookSecret = $site_settings->shopify_webhook_sign;

            $hmacLocal = base64_encode(hash_hmac('sha256', $data, $webhookSecret, true));
            if (!hash_equals($hmac, $hmacLocal)) {
                // Issue with HMAC or missing shop header
                $logger->debug('shopify:', ['error' => 'Invalid webhook signature']);
                return response()->json(['error' => 'Invalid webhook signature'], 422);
            }
            // ---------- 

            if (!$site_settings->shopify_integration_status) {
                $logger->debug('shopify:', ['error' => 'Shopify Integration is not enabled']);
                return response()->json(['error' => 'Shopify Integration is not enabled'], 422);
            }

            $shopify_id = 'gid://shopify/Order/' . $request->id;
            $receiptSocial = ReceiptSocial::where('shopify_id', $shopify_id)->where('website_setting_id', $site_settings->id)->first();
            if ($receiptSocial) {
                $receiptSocial->delete();
                $logger->debug('shopify:', ['success' => 'Success Shopify WebHook Order Deleted ' . $shopify_id]);
            } else {
                $logger->debug('shopify:', ['error' => 'Shopify WebHook Order Not Found']);
                return response()->json(['error' => 'Shopify WebHook Order Not Found'], 422);
            }
            return response()->json(null, 200);
        } catch (\Exception $e) {
            $logger->debug('shopify:', ['error' => 'Error Dispatch Shopify Order: ' . $e]);
            return response()->json(['error' => 'Error Dispatch Shopify Order: ' . $e], 422);
        }
    }
}
