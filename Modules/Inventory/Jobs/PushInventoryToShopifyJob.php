<?php

namespace Modules\Inventory\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Inventory\Entities\InventoryBalance;
use Modules\Inventory\Entities\InventoryChannelLink;
use Modules\Inventory\Entities\InventorySyncFailure;
use Modules\Inventory\Services\ShopifyInventoryClient;
use RuntimeException;

class PushInventoryToShopifyJob implements ShouldBeUniqueUntilProcessing, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $itemId)
    {
        $this->onConnection('database');
    }

    public function uniqueId(): string
    {
        return (string) $this->itemId;
    }

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function handle(ShopifyInventoryClient $client): void
    {
        $balance = InventoryBalance::where('inv_item_id', $this->itemId)->first();
        if (! $balance) {
            return;
        }

        $sent = (int) $balance->on_hand;
        $links = InventoryChannelLink::with('website')->where('inv_item_id', $this->itemId)->get();
        $failed = false;

        foreach ($links as $link) {
            $website = $link->website;
            $locationId = $link->shopify_location_id ?: $website?->shopify_location_id;
            if (! $website || ! $link->shopify_inventory_item_id || ! $locationId) {
                $this->recordFailure($link, $sent, 'موقع شوبيفاي أو معرّف المخزون غير مسجل على الربط.');
                $failed = true;
                continue;
            }

            $result = $client->setOnHand($website, $link->shopify_inventory_item_id, $locationId, $sent);
            $clamped = ! empty($result['clamped_from']);
            if ($client->failed($result)) {
                $this->recordFailure($link, $sent, $client->errorText($result));
                $failed = true;
                continue;
            }

            if ($clamped) {
                $this->recordFailure($link, 0, $client->errorText($result));
            } else {
                InventorySyncFailure::query()
                    ->where('inv_channel_link_id', $link->id)
                    ->whereNull('resolved_at')
                    ->update(['resolved_at' => now()]);
            }
        }

        $fresh = InventoryBalance::where('inv_item_id', $this->itemId)->first();
        if ($fresh && (int) $fresh->on_hand !== $sent) {
            self::dispatch($this->itemId);

            return;
        }

        if ($failed) {
            throw new RuntimeException('Shopify inventory push failed for item '.$this->itemId);
        }

        if ($fresh) {
            $fresh->sync_pending = false;
            $fresh->save();
        }
    }

    private function recordFailure(InventoryChannelLink $link, int $sent, string $error): void
    {
        InventorySyncFailure::query()
            ->where('inv_channel_link_id', $link->id)
            ->whereNull('resolved_at')
            ->update(['resolved_at' => now()]);

        InventorySyncFailure::create([
            'inv_item_id' => $link->inv_item_id,
            'inv_channel_link_id' => $link->id,
            'website_setting_id' => $link->website_setting_id,
            'quantity_sent' => $sent,
            'error' => mb_substr($error, 0, 2000),
        ]);
    }
}
