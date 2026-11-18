<?php

namespace Modules\Inventory\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Inventory\Entities\InventoryChannelLink;
use Modules\Inventory\Entities\InventoryItem;
use Modules\Inventory\Entities\InventorySyncFailure;
use Modules\Inventory\Entities\InventoryUnmappedEvent;
use Modules\Inventory\Jobs\PushInventoryToShopifyJob;
use Modules\Inventory\Services\ShopifyInventoryClient;
use Modules\Inventory\Support\ShopifyIds;
use Symfony\Component\HttpFoundation\Response;

class ShopifyWebController extends Controller
{
    public function index(Request $request, ShopifyInventoryClient $client)
    {
        abort_if(Gate::denies('inventory_shopify'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $websites = WebsiteSetting::where('shopify_integration_status', 1)->orderBy('site_name')->get();
        $website = $request->website_setting_id
            ? $websites->firstWhere('id', (int) $request->website_setting_id)
            : $websites->first();

        $variants = [];
        $page = ['has_next' => false, 'cursor' => null];
        $locations = [];
        $error = null;

        if ($website && $request->boolean('load')) {
            try {
                $page = $client->fetchVariants($website, $request->cursor);
                $variants = $page['variants'];
                $locations = $client->fetchLocations($website);
            } catch (\Throwable $exception) {
                $error = $exception->getMessage();
            }

        }

        $existing = collect();
        if ($website && $variants !== []) {
            $existing = InventoryChannelLink::with('item')
                ->where('website_setting_id', $website->id)
                ->whereIn('shopify_variant_id', collect($variants)->pluck('variant_id')->filter())
                ->get()
                ->keyBy('shopify_variant_id');
        }

        $items = InventoryItem::orderBy('name')->get(['id', 'name', 'sku']);
        $suggestions = [];
        foreach ($variants as $variant) {
            $suggestions[$variant['variant_id']] = $this->suggestItem($items, $variant['product_title'], $variant['variant_title']);
        }

        return view('inventory::admin.shopify.index', compact(
            'websites',
            'website',
            'variants',
            'page',
            'locations',
            'existing',
            'items',
            'suggestions',
            'error'
        ));
    }

    public function link(Request $request)
    {
        abort_if(Gate::denies('inventory_shopify'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'website_setting_id' => ['required', 'integer', 'exists:website_settings,id'],
            'shopify_variant_id' => ['required', 'string', 'max:64'],
            'shopify_inventory_item_id' => ['required', 'string', 'max:64'],
            'shopify_location_id' => ['nullable', 'string', 'max:64'],
            'product_title' => ['nullable', 'string', 'max:255'],
            'variant_title' => ['nullable', 'string', 'max:255'],
            'inv_item_id' => ['nullable', 'integer', 'exists:inv_items,id'],
        ]);

        $variantId = ShopifyIds::numeric($data['shopify_variant_id']);
        $website = WebsiteSetting::findOrFail($data['website_setting_id']);

        if (empty($data['inv_item_id'])) {
            InventoryChannelLink::where('website_setting_id', $website->id)
                ->where('shopify_variant_id', $variantId)
                ->delete();
            toast('تم فك ربط المتغير', 'success');

            return redirect()->back();
        }

        $link = InventoryChannelLink::updateOrCreate(
            [
                'website_setting_id' => $website->id,
                'shopify_variant_id' => $variantId,
            ],
            [
                'inv_item_id' => $data['inv_item_id'],
                'shopify_inventory_item_id' => ShopifyIds::numeric($data['shopify_inventory_item_id']),
                'shopify_location_id' => ShopifyIds::numeric($data['shopify_location_id'] ?? null) ?: $website->shopify_location_id,
                'product_title' => $data['product_title'] ?? null,
                'variant_title' => $data['variant_title'] ?? null,
            ]
        );

        InventoryUnmappedEvent::query()
            ->where('website_setting_id', $website->id)
            ->where('shopify_variant_id', $variantId)
            ->whereNull('resolved_at')
            ->update(['resolved_at' => now()]);

        PushInventoryToShopifyJob::dispatch($link->inv_item_id);
        toast('تم ربط متغير شوبيفاي. الرصيد هيتبعت للطابور.', 'success');

        return redirect()->back();
    }

    public function saveLocation(Request $request)
    {
        abort_if(Gate::denies('inventory_shopify'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'website_setting_id' => ['required', 'integer', 'exists:website_settings,id'],
            'shopify_location_id' => ['required', 'string', 'max:64'],
        ]);

        $website = WebsiteSetting::findOrFail($data['website_setting_id']);
        $website->shopify_location_id = ShopifyIds::numeric($data['shopify_location_id']);
        $website->save();

        toast('تم حفظ موقع شوبيفاي اللي هيتحدث رصيده', 'success');

        return redirect()->back();
    }

    public function unmapped()
    {
        abort_if(Gate::denies('inventory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $events = InventoryUnmappedEvent::with('website')
            ->whereNull('resolved_at')
            ->latest('id')
            ->paginate(40);

        return view('inventory::admin.shopify.unmapped', compact('events'));
    }

    public function failures()
    {
        abort_if(Gate::denies('inventory_shopify'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $failures = InventorySyncFailure::with(['item', 'website'])
            ->whereNull('resolved_at')
            ->latest('id')
            ->paginate(40);

        return view('inventory::admin.shopify.failures', compact('failures'));
    }

    public function retry(InventorySyncFailure $failure)
    {
        abort_if(Gate::denies('inventory_shopify'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PushInventoryToShopifyJob::dispatch($failure->inv_item_id);
        toast('تمت إعادة جدولة الإرسال', 'success');

        return redirect()->back();
    }

    private function suggestItem($items, string $productTitle, string $variantTitle): ?int
    {
        $product = mb_strtolower(trim($productTitle));
        $variant = mb_strtolower(trim($variantTitle));
        $full = trim($product.' '.$variant);

        foreach ($items as $item) {
            $name = mb_strtolower(trim($item->name));
            if ($name !== '' && ($name === $product || $name === $variant || $name === $full)) {
                return $item->id;
            }
        }

        return null;
    }
}
