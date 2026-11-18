<?php

namespace Modules\Inventory\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Inventory\Entities\InventoryItem;
use Modules\Inventory\Exceptions\InventoryException;
use Modules\Inventory\Jobs\PushInventoryToShopifyJob;
use Modules\Inventory\Services\InventoryLedger;
use Symfony\Component\HttpFoundation\Response;

class ItemWebController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('inventory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $items = InventoryItem::with('balance')
            ->withCount(['socialLinks', 'channelLinks'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%'.$search.'%')
                        ->orWhere('sku', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view('inventory::admin.items.index', compact('items'));
    }

    public function create()
    {
        abort_if(Gate::denies('inventory_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('inventory::admin.items.create');
    }

    public function store(Request $request, InventoryLedger $ledger)
    {
        abort_if(Gate::denies('inventory_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:inv_items,sku'],
        ]);

        $item = InventoryItem::create([
            'name' => $data['name'],
            'sku' => ($data['sku'] ?? '') !== '' ? $data['sku'] : null,
        ]);
        $ledger->ensureBalance($item);

        toast('تم إنشاء كارت الصنف', 'success');

        return redirect()->route('admin.inventory.items.show', $item);
    }

    public function show(InventoryItem $item)
    {
        abort_if(Gate::denies('inventory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $item->load(['balance', 'socialLinks.product.website', 'channelLinks.website']);
        $movements = $item->movements()->with('user')->latest('id')->paginate(30);

        return view('inventory::admin.items.show', compact('item', 'movements'));
    }

    public function update(Request $request, InventoryItem $item)
    {
        abort_if(Gate::denies('inventory_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:inv_items,sku,'.$item->id],
        ]);

        $sku = ($data['sku'] ?? '') !== '' ? $data['sku'] : null;
        if ($item->sku === (string) config('inventory.open_stock_sku', 'open-stock')) {
            $sku = $item->sku;
        }

        $item->update([
            'name' => $data['name'],
            'sku' => $sku,
        ]);

        toast('تم تحديث كارت الصنف', 'success');

        return redirect()->route('admin.inventory.items.show', $item);
    }

    public function adjust(Request $request, InventoryItem $item, InventoryLedger $ledger)
    {
        abort_if(Gate::denies('inventory_adjust'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'on_hand' => ['required', 'integer', 'min:0'],
            'note' => ['required', 'string', 'max:1000'],
        ]);

        $hasMovements = $item->movements()->exists();
        $type = $hasMovements ? 'adjustment' : 'opening';

        try {
            $ledger->setOnHand($item, (int) $data['on_hand'], $type, $data['note']);
        } catch (InventoryException $exception) {
            alert($exception->getMessage(), '', 'error');

            return redirect()->back()->withInput();
        }

        toast($type === 'opening' ? 'تم تسجيل الرصيد الافتتاحي' : 'تم تسجيل التسوية', 'success');

        return redirect()->route('admin.inventory.items.show', $item);
    }

    public function sync(InventoryItem $item)
    {
        abort_if(Gate::denies('inventory_shopify'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PushInventoryToShopifyJob::dispatch($item->id);
        toast('تم جدولة إرسال الرصيد لشوبيفاي', 'success');

        return redirect()->route('admin.inventory.items.show', $item);
    }

    public function destroy(InventoryItem $item)
    {
        abort_if(Gate::denies('inventory_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($item->sku === (string) config('inventory.open_stock_sku', 'open-stock')) {
            alert('كارت Open stock هو الاحتياطي للمنتجات غير المربوطة، ومش بيتشال.', '', 'error');

            return redirect()->back();
        }

        $onHand = (int) ($item->balance->on_hand ?? 0);
        if ($onHand !== 0) {
            alert('لا يمكن حذف صنف رصيده ليس صفراً. سجّل تسوية إلى صفر أولاً.', '', 'error');

            return redirect()->back();
        }

        $item->delete();
        toast('تم حذف كارت الصنف', 'success');

        return redirect()->route('admin.inventory.items.index');
    }
}
