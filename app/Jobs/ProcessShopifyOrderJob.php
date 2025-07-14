<?php

namespace App\Jobs;

use App\Models\ReceiptSocial;
use App\Models\ReceiptSocialProduct;
use App\Models\ReceiptSocialProductPivot;
use App\Models\WebsiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessShopifyOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private array $orderData,
        private WebsiteSetting $siteSettings
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $logger = Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/shopify.log'),
            'level' => 'debug',
        ]);

        try {
            $shopify_id = $this->orderData['admin_graphql_api_id'];
            $shopify_order_num = $this->orderData['order_number'];
            $products = $this->orderData['line_items'];
            $shipping_address = $this->orderData['shipping_address'];
            $shipping_cost = $this->orderData['current_shipping_price_set']['shop_money']['amount'];
            $total = $this->orderData['current_total_price'];

            $customer_name = $shipping_address['name'];
            $customer_phone = $shipping_address['phone'];
            $customer_address = $shipping_address['address1'] . ', ' . $shipping_address['address2'] . ', ' . $shipping_address['city'] . ', ' . $shipping_address['province'] . ', ' . $shipping_address['country'] . ', ' . $shipping_address['zip'];

            $receiptSocial = ReceiptSocial::where('shopify_id', $shopify_id)->where('website_setting_id', $this->siteSettings->id)->first();
            $is_new_order = false;
            if (!$receiptSocial) {
                $receiptSocial = new ReceiptSocial();
                $receiptSocial->shopify_id = $shopify_id;
                $receiptSocial->shopify_order_num = $shopify_order_num;
                $receiptSocial->website_setting_id = $this->siteSettings->id;
                $is_new_order = true;
            }
            $receiptSocial->client_name = $customer_name;
            $receiptSocial->client_type = 'individual';
            $receiptSocial->phone_number = $customer_phone;
            $receiptSocial->total_cost = $total - $shipping_cost;
            $receiptSocial->shipping_country_cost = $shipping_cost;
            $receiptSocial->shipping_address = $customer_address;
            $receiptSocial->save();

            $updatedOrCreatedProducts = [];
            foreach ($products as $product) {
                if ($product['current_quantity'] > 0) {
                    $receiptSocialProduct = ReceiptSocialProduct::updateOrCreate(
                        [
                            'website_setting_id' => $this->siteSettings->id,
                            'shopify_id' => $product['product_id'],
                            'name' => $product['name'],
                        ],
                        [
                            'price' => $product['price'],
                        ]
                    );

                    $updatedOrCreatedProducts[] = $product['admin_graphql_api_id'];

                    $receiptSocialProductPivot = ReceiptSocialProductPivot::updateOrCreate(
                        [
                            'receipt_social_id' => $receiptSocial->id,
                            'shopify_id' => $product['admin_graphql_api_id'],
                        ],
                        [
                            'receipt_social_product_id' => $receiptSocialProduct->id,
                            'title' => $product['name'],
                            'quantity' => $product['quantity'],
                            'price' => $product['price'],
                            'total_cost' => $product['price'] * $product['quantity'],
                        ]
                    );

                    if ($is_new_order || $receiptSocialProductPivot->photos == null) {
                        $itemProperties = $product['properties'];
                        $photos = null;
                        if (!empty($itemProperties)) {
                            foreach ($itemProperties as $property) {
                                if (filter_var($property['value'], FILTER_VALIDATE_URL)) {
                                    $photos[0]['photo'] = $property['value'];
                                } else {
                                    $photos[0]['note'] = $property['value'];
                                }
                            }
                        }
                        $receiptSocialProductPivot->photos = $photos ? json_encode($photos, JSON_UNESCAPED_SLASHES) : null;
                        $receiptSocialProductPivot->save();
                    }
                }
            }

            // Delete Products that are not in the request
            $receiptSocialProducts = ReceiptSocialProductPivot::where('receipt_social_id', $receiptSocial->id)
                ->whereNotIn('shopify_id', $updatedOrCreatedProducts)->get();

            foreach ($receiptSocialProducts as $receiptSocialProduct) {
                $receiptSocialProduct->delete();
            }

            $logger->debug('shopify:', ['success' => 'Success Shopify WebHook Order ' . ($is_new_order ? 'Created' : 'Updated') . ' ' . $shopify_order_num]);
        } catch (\Exception $e) {
            $logger->debug('shopify:', ['error' => 'Error Dispatch Shopify Order: ' . $e->getMessage()]);
            throw $e;
        }
    }
}
