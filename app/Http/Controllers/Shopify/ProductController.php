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

class ProductController extends Controller
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
            
            // todo: create or update product

            $logger->debug('shopify:', ['success' => 'Success Shopify WebHook Order Created']);
            return response()->json(null, 200);
        } catch (\Exception $e) {
            $logger->debug('shopify:', ['error' => 'Error Dispatch Shopify Order: ' . $e]);
            return response()->json(['error' => 'Error Dispatch Shopify Order: ' . $e], 422);
        }
    } 
}
