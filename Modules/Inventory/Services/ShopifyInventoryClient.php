<?php

namespace Modules\Inventory\Services;

use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Http;
use Modules\Inventory\Support\ShopifyIds;

class ShopifyInventoryClient
{
    public function setOnHand(WebsiteSetting $site, string $inventoryItemId, string $locationId, int $quantity): array
    {
        $result = $this->sendQuantity($site, $inventoryItemId, $locationId, $quantity);
        if ($quantity < 0 && $this->failed($result)) {
            $fallback = $this->sendQuantity($site, $inventoryItemId, $locationId, 0);
            $fallback['clamped_from'] = $quantity;

            return $fallback;
        }

        return $result;
    }

    public function fetchLocations(WebsiteSetting $site): array
    {
        $payload = $this->graphql($site, <<<'GRAPHQL'
            query InventoryLocations {
                locations(first: 20) {
                    edges {
                        node { id name isActive }
                    }
                }
            }
        GRAPHQL);
        $this->throwIfFailed($payload);

        $locations = [];
        foreach ($payload['data']['locations']['edges'] ?? [] as $edge) {
            $node = $edge['node'];
            $locations[] = [
                'id' => ShopifyIds::numeric($node['id'] ?? null),
                'gid' => $node['id'] ?? null,
                'name' => $node['name'] ?? '',
                'is_active' => (bool) ($node['isActive'] ?? false),
            ];
        }

        return $locations;
    }

    public function fetchVariants(WebsiteSetting $site, ?string $cursor = null): array
    {
        $payload = $this->graphql($site, <<<'GRAPHQL'
            query InventoryVariants($cursor: String) {
                products(first: 25, after: $cursor) {
                    pageInfo { hasNextPage endCursor }
                    edges {
                        node {
                            id
                            title
                            variants(first: 100) {
                                edges {
                                    node {
                                        id
                                        title
                                        sku
                                        inventoryItem { id }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        GRAPHQL, ['cursor' => $cursor]);
        $this->throwIfFailed($payload);

        $products = $payload['data']['products'] ?? [];
        $variants = [];
        foreach ($products['edges'] ?? [] as $productEdge) {
            $product = $productEdge['node'];
            foreach ($product['variants']['edges'] ?? [] as $variantEdge) {
                $variant = $variantEdge['node'];
                $variants[] = [
                    'product_title' => $product['title'] ?? '',
                    'variant_title' => $variant['title'] ?? '',
                    'sku' => $variant['sku'] ?? '',
                    'variant_id' => ShopifyIds::numeric($variant['id'] ?? null),
                    'inventory_item_id' => ShopifyIds::numeric($variant['inventoryItem']['id'] ?? null),
                ];
            }
        }

        return [
            'variants' => $variants,
            'has_next' => (bool) ($products['pageInfo']['hasNextPage'] ?? false),
            'cursor' => $products['pageInfo']['endCursor'] ?? null,
        ];
    }

    private function throwIfFailed(array $result): void
    {
        if ($this->failed($result)) {
            throw new \RuntimeException($this->errorText($result));
        }
    }

    public function failed(array $result): bool
    {
        if (! empty($result['clamped_from']) && empty($result['errors']) && empty($result['user_errors'])) {
            return false;
        }

        return ! empty($result['errors']) || ! empty($result['user_errors']) || ! empty($result['exception']);
    }

    public function errorText(array $result): string
    {
        $parts = [];
        if (! empty($result['exception'])) {
            $parts[] = $result['exception'];
        }
        foreach ($result['errors'] ?? [] as $error) {
            $parts[] = is_array($error) ? ($error['message'] ?? json_encode($error)) : (string) $error;
        }
        foreach ($result['user_errors'] ?? [] as $error) {
            $parts[] = is_array($error) ? ($error['message'] ?? json_encode($error)) : (string) $error;
        }
        if (! empty($result['clamped_from'])) {
            $parts[] = 'الرصيد المحلي '.$result['clamped_from'].' سالب، واتبعت 0 لشوبيفاي.';
        }

        return $parts === [] ? 'فشل غير معروف من شوبيفاي.' : implode(' | ', $parts);
    }

    private function sendQuantity(WebsiteSetting $site, string $inventoryItemId, string $locationId, int $quantity): array
    {
        $query = <<<'GRAPHQL'
            mutation InventorySetOnHand($input: InventorySetQuantitiesInput!) {
                inventorySetQuantities(input: $input) {
                    userErrors { field message code }
                }
            }
        GRAPHQL;

        return $this->graphql($site, $query, [
            'input' => [
                'name' => 'on_hand',
                'reason' => 'correction',
                'ignoreCompareQuantity' => true,
                'quantities' => [[
                    'inventoryItemId' => ShopifyIds::gid($inventoryItemId, 'InventoryItem'),
                    'locationId' => ShopifyIds::gid($locationId, 'Location'),
                    'quantity' => $quantity,
                ]],
            ],
        ], 'inventorySetQuantities');
    }

    private function graphql(WebsiteSetting $site, string $query, array $variables = [], ?string $mutationName = null): array
    {
        if (! $site->shopify_domain || ! $site->shopify_access_token) {
            return ['errors' => [['message' => 'بيانات شوبيفاي ناقصة على الموقع.']]];
        }

        $version = config('inventory.shopify_api_version', '2025-04');
        $url = 'https://'.$site->shopify_domain.'/admin/api/'.$version.'/graphql.json';

        try {
            $response = Http::withHeaders([
                'X-Shopify-Access-Token' => $site->shopify_access_token,
                'Content-Type' => 'application/json',
            ])->timeout(20)->post($url, [
                'query' => $query,
                'variables' => $variables,
            ]);
        } catch (\Throwable $exception) {
            return ['exception' => $exception->getMessage()];
        }

        $json = $response->json() ?? [];
        if (! $response->successful()) {
            return [
                'errors' => [['message' => 'Shopify HTTP '.$response->status().' '.$response->body()]],
            ];
        }

        $userErrors = [];
        if ($mutationName && isset($json['data'][$mutationName]['userErrors'])) {
            $userErrors = $json['data'][$mutationName]['userErrors'];
        }

        return [
            'data' => $json['data'] ?? [],
            'errors' => $json['errors'] ?? [],
            'user_errors' => $userErrors,
        ];
    }
}
