<?php

namespace App\Jobs;

use App\Models\ReceiptSocialProduct;
use App\Models\WebsiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VairantShopifyMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $receiptSocialProduct;
    protected $siteSettings;
    /**
     * Create a new job instance.
     */
    public function __construct(ReceiptSocialProduct $receiptSocialProduct, WebsiteSetting $siteSettings)
    {
        $this->receiptSocialProduct = $receiptSocialProduct;
        $this->siteSettings = $siteSettings;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $variantId = $this->receiptSocialProduct->shopify_variant_id;

            if (!$variantId) {
                Log::warning('No Shopify variant ID found for product', [
                    'product_id' => $this->receiptSocialProduct->id
                ]);
                return;
            }

            $graphqlQuery = <<<'GRAPHQL'
            query GetVariantWithAllImages($variantId: ID!) {
                productVariant(id: $variantId) {
                    id
                    title
                    image {
                        id
                        originalSrc
                    }
                    product {
                        images(first: 15) {
                            edges {
                                node {
                                    id
                                    originalSrc 
                                }
                            }
                        }
                    }
                }
            }
            GRAPHQL;

            $response = Http::withHeaders([
                'X-Shopify-Access-Token' => $this->siteSettings->shopify_access_token,
                'Content-Type' => 'application/json',
            ])->post($this->getShopifyGraphQLUrl(), [
                'query' => $graphqlQuery,
                'variables' => [
                    'variantId' => 'gid://shopify/ProductVariant/' . $variantId
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']['productVariant'])) {
                    $variantData = $data['data']['productVariant'];
                    $this->processVariantData($variantData); 
                } else {
                    Log::error('No variant data found in Shopify response', [
                        'response' => $data,
                        'variant_id' => $variantId
                    ]);
                }
            } else {
                Log::error('Shopify GraphQL request failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'variant_id' => $variantId
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error in ProductShopifyJob', [
                'message' => $e->getMessage(),
                'product_id' => $this->receiptSocialProduct->id,
                'variant_id' => $variantId ?? null
            ]);
        }
    }

    /**
     * Get the Shopify GraphQL URL
     */
    private function getShopifyGraphQLUrl(): string
    {
        $shopDomain = $this->siteSettings->shopify_domain ?? 'your-shop.myshopify.com';
        return "https://{$shopDomain}/admin/api/2025-04/graphql.json";
    }

    /**
     * Process the variant data from Shopify
     */
    private function processVariantData(array $variantData): void
    { 
        // Extract variant image
        $variantImage = $variantData['image'] ?? null;

        // Extract product images
        $productImages = [];
        if (isset($variantData['product']['images']['edges'])) {
            foreach ($variantData['product']['images']['edges'] as $edge) {
                $productImages[] = $edge['node'];
            }
        }  

        $this->saveVariantImages($variantImage, $productImages);
    }

    /**
     * Save variant images to the database
     */
    private function saveVariantImages(?array $variantImage, array $productImages): void
    {
        $shopifyImages = [];
        // Save variant image if exists
        if ($variantImage) {
            $shopifyImages[] = $variantImage['originalSrc'];
        } 

        // foreach ($productImages as $image) {
        //     $shopifyImages[] = $image['originalSrc'];
        // }

        // Update the product with image data
        $this->receiptSocialProduct->shopify_images = json_encode($shopifyImages);
        $this->receiptSocialProduct->save();
    }
}
