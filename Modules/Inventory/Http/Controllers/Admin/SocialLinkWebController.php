<?php

namespace Modules\Inventory\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReceiptSocialProduct;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Inventory\Entities\InventoryItem;
use Modules\Inventory\Entities\InventorySocialLink;
use Symfony\Component\HttpFoundation\Response;

class SocialLinkWebController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('inventory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = ReceiptSocialProduct::with('website')
            ->when($request->website_setting_id, fn ($query, $id) => $query->where('website_setting_id', $id))
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->when($request->unlinked, function ($query) {
                $query->whereDoesntHave('inventorySocialLink');
            })
            ->orderBy('name')
            ->paginate(40)
            ->withQueryString();

        $links = InventorySocialLink::with('item')
            ->whereIn('receipt_social_product_id', $products->pluck('id'))
            ->get()
            ->keyBy('receipt_social_product_id');

        $items = InventoryItem::orderBy('name')->get(['id', 'name', 'sku']);
        $websites = WebsiteSetting::orderBy('site_name')->get(['id', 'site_name']);

        return view('inventory::admin.social_links.index', compact('products', 'links', 'items', 'websites'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('inventory_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'receipt_social_product_id' => ['required', 'integer', 'exists:receipt_social_products,id'],
            'inv_item_id' => ['nullable', 'integer', 'exists:inv_items,id'],
        ]);

        if (empty($data['inv_item_id'])) {
            InventorySocialLink::where('receipt_social_product_id', $data['receipt_social_product_id'])->delete();
            toast('تم فك ربط المنتج', 'success');
        } else {
            InventorySocialLink::updateOrCreate(
                ['receipt_social_product_id' => $data['receipt_social_product_id']],
                ['inv_item_id' => $data['inv_item_id']]
            );
            toast('تم ربط منتج السوشيال بكارت المخزون', 'success');
        }

        return redirect()->back();
    }
}
