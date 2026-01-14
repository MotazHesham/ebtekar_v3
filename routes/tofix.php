<?php

use Illuminate\Support\Facades\Route; 

Route::get('admin/fix/receipt-socials-products/images', function () {
    $receiptSocialProducts = \App\Models\ReceiptSocialProduct::where('shopify_variant_id', '!=', null)
        ->where(function($q){
            $q->whereNull('shopify_images')
                ->orWhere('shopify_images', '=', '')
                ->orWhere('shopify_images', '=', '[]');
        })->get();
    return count($receiptSocialProducts);
    foreach ($receiptSocialProducts as $receiptSocialProduct) { 
        \App\Jobs\VairantShopifyMediaJob::dispatch($receiptSocialProduct, $receiptSocialProduct->siteSettings);
    }
    return 'done';
});
