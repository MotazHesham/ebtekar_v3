<?php

namespace Modules\Inventory\Services;

use App\Models\ReceiptSocial;
use App\Models\ReceiptSocialProduct;
use App\Models\ReceiptSocialProductPivot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\InventoryBalance;
use Modules\Inventory\Entities\InventoryChannelLink;
use Modules\Inventory\Entities\InventoryItem;
use Modules\Inventory\Entities\InventoryMovement;
use Modules\Inventory\Entities\InventorySocialLink;
use Modules\Inventory\Entities\InventoryUnmappedEvent;
use Modules\Inventory\Exceptions\InventoryException;
use Modules\Inventory\Jobs\PushInventoryToShopifyJob;
use Modules\Inventory\Support\ShopifyIds;

class InventoryLedger
{
    public function syncPivot(ReceiptSocialProductPivot $pivot, array $context = []): void
    {
        $this->transaction(function () use ($pivot, $context) {
            $this->syncPivotWithin($pivot, $context);
        });
    }

    public function releasePivot(ReceiptSocialProductPivot $pivot, array $context = []): void
    {
        $this->transaction(function () use ($pivot, $context) {
            $this->releasePivotWithin($pivot, $context);
        });
    }

    public function clearBoxDetails(ReceiptSocialProductPivot $pivot, array $context = []): void
    {
        $this->transaction(function () use ($pivot, $context) {
            $pivot->load('boxDetails');
            foreach ($pivot->boxDetails as $detail) {
                $detailContext = $context;
                $detailContext['receipt_social_product_id'] = $detail->receipt_social_product_id;
                unset($detailContext['shopify_variant_id'], $detailContext['quantity']);
                $this->applySource($detail, 'box_detail', 0, $detailContext);
            }
            $pivot->boxDetails()->delete();
        });
    }

    public function releaseReceipt(ReceiptSocial $receipt, array $context = []): void
    {
        $this->transaction(function () use ($receipt, $context) {
            $receipt->load('receiptsReceiptSocialProducts.boxDetails');
            foreach ($receipt->receiptsReceiptSocialProducts as $pivot) {
                $this->releasePivotWithin($pivot, $context);
            }
        });
    }

    public function reverseReceipt(ReceiptSocial $receipt): void
    {
        $this->transaction(function () use ($receipt) {
            $receipt->refresh();
            if ($receipt->inventory_reversed) {
                return;
            }

            $receipt->load('receiptsReceiptSocialProducts.boxDetails');
            foreach ($receipt->receiptsReceiptSocialProducts as $pivot) {
                $this->releasePivotWithin($pivot, [
                    'allow_negative' => true,
                    'allow_unmapped' => true,
                ]);
            }

            $receipt->inventory_reversed = true;
            $receipt->save();
        });
    }

    public function reapplyReceipt(ReceiptSocial $receipt, array $context = []): void
    {
        $this->transaction(function () use ($receipt, $context) {
            $receipt->refresh();
            $receipt->inventory_reversed = false;
            $receipt->save();

            $receipt->load('receiptsReceiptSocialProducts.boxDetails');
            foreach ($receipt->receiptsReceiptSocialProducts as $pivot) {
                $this->syncPivotWithin($pivot, $context);
            }
        });
    }

    public function setOnHand(InventoryItem $item, int $quantity, string $type, ?string $note = null): void
    {
        if (! in_array($type, ['opening', 'adjustment'], true)) {
            throw new InventoryException('نوع حركة المخزون غير معروف.');
        }

        $this->transaction(function () use ($item, $quantity, $type, $note) {
            $balance = $this->lockedBalance($item->id);
            $delta = $quantity - (int) $balance->on_hand;
            if ($delta === 0) {
                return;
            }

            $balance->on_hand = $quantity;
            $balance->sync_pending = true;
            $balance->save();

            InventoryMovement::create([
                'inv_item_id' => $item->id,
                'quantity_delta' => $delta,
                'on_hand_after' => $quantity,
                'type' => $type,
                'note' => $note,
                'user_id' => auth()->id(),
            ]);

            $this->queuePush($item->id);
        });
    }

    public function ensureBalance(InventoryItem $item): InventoryBalance
    {
        return $this->transaction(function () use ($item) {
            return $this->lockedBalance($item->id);
        });
    }

    private function syncPivotWithin(ReceiptSocialProductPivot $pivot, array $context): void
    {
        $pivot->refresh();
        $pivot->load('boxDetails', 'receipt');

        $reversed = (bool) ($pivot->receipt->inventory_reversed ?? false);
        $context['receipt_social_id'] = $pivot->receipt_social_id;
        $context['website_setting_id'] = $context['website_setting_id'] ?? $pivot->receipt->website_setting_id ?? null;

        if (($pivot->type ?? 'single') === 'box') {
            $this->applySource($pivot, 'pivot', 0, $context);
            foreach ($pivot->boxDetails as $detail) {
                $detailContext = $context;
                $detailContext['receipt_social_product_id'] = $detail->receipt_social_product_id;
                unset($detailContext['shopify_variant_id'], $detailContext['quantity']);
                $desired = $reversed ? 0 : (int) $detail->quantity;
                $this->applySource($detail, 'box_detail', $desired, $detailContext);
            }

            return;
        }

        foreach ($pivot->boxDetails as $detail) {
            $detailContext = $context;
            $detailContext['receipt_social_product_id'] = $detail->receipt_social_product_id;
            unset($detailContext['shopify_variant_id'], $detailContext['quantity']);
            $this->applySource($detail, 'box_detail', 0, $detailContext);
        }

        $desired = $reversed ? 0 : (int) ($context['quantity'] ?? $pivot->quantity);
        $context['receipt_social_product_id'] = $pivot->receipt_social_product_id;
        $this->applySource($pivot, 'pivot', $desired, $context);
    }

    private function releasePivotWithin(ReceiptSocialProductPivot $pivot, array $context): void
    {
        $pivot->loadMissing('boxDetails', 'receipt');
        $context['receipt_social_id'] = $pivot->receipt_social_id;
        $context['website_setting_id'] = $context['website_setting_id'] ?? $pivot->receipt->website_setting_id ?? null;

        foreach ($pivot->boxDetails as $detail) {
            $detailContext = $context;
            $detailContext['receipt_social_product_id'] = $detail->receipt_social_product_id;
            unset($detailContext['shopify_variant_id'], $detailContext['quantity']);
            $this->applySource($detail, 'box_detail', 0, $detailContext);
        }

        $context['receipt_social_product_id'] = $pivot->receipt_social_product_id;
        unset($context['quantity']);
        $this->applySource($pivot, 'pivot', 0, $context);
    }

    private function applySource(Model $source, string $sourceType, int $desired, array $context): void
    {
        $applied = (int) ($source->stock_applied_qty ?? 0);
        $baseline = (int) ($source->stock_baseline_qty ?? 0);
        if ($desired === $applied) {
            return;
        }

        $previousLedger = max(0, $applied - $baseline);
        $newLedger = max(0, $desired - $baseline);
        $deltaLedger = $newLedger - $previousLedger;

        if ($deltaLedger === 0) {
            $source->stock_applied_qty = $desired;
            $source->save();

            return;
        }

        $item = $this->resolveItem($source, $sourceType, $context, $applied);
        if (! $item) {
            if (! empty($context['allow_unmapped'])) {
                $this->logUnmapped($source, $sourceType, $context);
                return;
            }

            throw new InventoryException('المنتج غير مربوط بكارت مخزون: '.$this->productName($source, $context));
        }

        if ($this->isOpenStock($item) && empty($context['note'])) {
            $context['note'] = 'منتج غير مربوط، اتخصم من Open stock';
        }

        $balance = $this->lockedBalance($item->id);
        $newOnHand = (int) $balance->on_hand - $deltaLedger;
        if ($newOnHand < 0 && empty($context['allow_negative']) && ! $this->isOpenStock($item)) {
            throw new InventoryException(
                'الرصيد غير كافٍ للصنف '.$item->name.'. المتاح '.$balance->on_hand.' والمطلوب خصمه '.max($deltaLedger, 0).'.'
            );
        }

        $balance->on_hand = $newOnHand;
        $balance->sync_pending = true;
        $balance->save();

        InventoryMovement::create([
            'inv_item_id' => $item->id,
            'quantity_delta' => -$deltaLedger,
            'on_hand_after' => $newOnHand,
            'type' => $deltaLedger > 0 ? 'social_sale' : 'social_return',
            'source_type' => $sourceType,
            'source_id' => $source->getKey(),
            'note' => $context['note'] ?? null,
            'user_id' => auth()->id(),
        ]);

        $source->stock_applied_qty = $desired;
        $source->save();

        $this->queuePush($item->id);
    }

    private function resolveItem(Model $source, string $sourceType, array $context, int $applied): ?InventoryItem
    {
        $variantId = $context['shopify_variant_id'] ?? null;
        if ($variantId) {
            $websiteId = $context['website_setting_id'] ?? null;
            if ($websiteId) {
                $link = InventoryChannelLink::query()
                    ->where('website_setting_id', $websiteId)
                    ->where('shopify_variant_id', ShopifyIds::numeric((string) $variantId))
                    ->first();
                if ($link) {
                    return $link->item;
                }
            }

            if ($applied > 0) {
                return $this->itemFromMovements($sourceType, (int) $source->getKey())
                    ?? $this->openStockItem();
            }

            return $this->openStockItem();
        }

        if ($sourceType === 'pivot' && ! empty($source->shopify_id)) {
            return $this->itemFromMovements($sourceType, (int) $source->getKey())
                ?? $this->itemFromSocialProduct($source->receipt_social_product_id ?? null)
                ?? $this->openStockItem();
        }

        $productId = $context['receipt_social_product_id'] ?? ($source->receipt_social_product_id ?? null);

        return $this->itemFromSocialProduct($productId)
            ?? $this->itemFromMovements($sourceType, (int) $source->getKey())
            ?? $this->openStockItem();
    }

    private function openStockItem(): InventoryItem
    {
        $sku = (string) config('inventory.open_stock_sku', 'open-stock');
        $item = InventoryItem::withTrashed()->where('sku', $sku)->first();

        if ($item) {
            if ($item->trashed()) {
                $item->restore();
            }

            return $item;
        }

        $item = InventoryItem::create([
            'name' => 'Open stock',
            'sku' => $sku,
        ]);
        $this->lockedBalance($item->id);

        return $item;
    }

    private function isOpenStock(InventoryItem $item): bool
    {
        return $item->sku === (string) config('inventory.open_stock_sku', 'open-stock');
    }

    private function itemFromSocialProduct($productId): ?InventoryItem
    {
        if (! $productId) {
            return null;
        }

        $link = InventorySocialLink::where('receipt_social_product_id', $productId)->first();

        return $link?->item;
    }

    private function itemFromMovements(string $sourceType, int $sourceId): ?InventoryItem
    {
        $movement = InventoryMovement::query()
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->latest('id')
            ->first();

        return $movement?->item;
    }

    private function lockedBalance(int $itemId): InventoryBalance
    {
        $balance = InventoryBalance::where('inv_item_id', $itemId)->lockForUpdate()->first();
        if ($balance) {
            return $balance;
        }

        try {
            InventoryBalance::create([
                'inv_item_id' => $itemId,
                'on_hand' => 0,
                'sync_pending' => false,
            ]);
        } catch (QueryException $exception) {
            // Another transaction created the row first.
        }

        return InventoryBalance::where('inv_item_id', $itemId)->lockForUpdate()->firstOrFail();
    }

    private function queuePush(int $itemId): void
    {
        if (! InventoryChannelLink::where('inv_item_id', $itemId)->exists()) {
            return;
        }

        DB::afterCommit(function () use ($itemId) {
            PushInventoryToShopifyJob::dispatch($itemId);
        });
    }

    private function logUnmapped(Model $source, string $sourceType, array $context): void
    {
        $variantId = isset($context['shopify_variant_id'])
            ? ShopifyIds::numeric((string) $context['shopify_variant_id'])
            : null;

        InventoryUnmappedEvent::updateOrCreate(
            [
                'website_setting_id' => $context['website_setting_id'] ?? null,
                'shopify_variant_id' => $variantId,
                'source_type' => $sourceType,
                'source_id' => $source->getKey(),
            ],
            [
                'receipt_social_id' => $context['receipt_social_id'] ?? ($source->receipt_social_id ?? null),
                'receipt_social_product_id' => $context['receipt_social_product_id'] ?? ($source->receipt_social_product_id ?? null),
                'product_name' => $this->productName($source, $context),
                'message' => 'المتغير غير مربوط بكارت مخزون، والأوردر اتسجل من غير خصم.',
                'resolved_at' => null,
            ]
        );
    }

    private function productName(Model $source, array $context): string
    {
        if (! empty($context['product_name'])) {
            return (string) $context['product_name'];
        }

        $productId = $context['receipt_social_product_id'] ?? ($source->receipt_social_product_id ?? null);
        if ($productId) {
            $name = ReceiptSocialProduct::withTrashed()->where('id', $productId)->value('name');
            if ($name) {
                return $name;
            }
        }

        if (! empty($source->title)) {
            return (string) $source->title;
        }

        return '#'.$source->getKey();
    }

    private function transaction(callable $callback): mixed
    {
        if (DB::transactionLevel() > 0) {
            return $callback();
        }

        return DB::transaction($callback);
    }
}
