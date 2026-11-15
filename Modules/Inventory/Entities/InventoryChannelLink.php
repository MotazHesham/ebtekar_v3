<?php

namespace Modules\Inventory\Entities;

use App\Models\WebsiteSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryChannelLink extends Model
{
    protected $table = 'inv_channel_links';

    protected $fillable = [
        'inv_item_id',
        'website_setting_id',
        'shopify_variant_id',
        'shopify_inventory_item_id',
        'shopify_location_id',
        'product_title',
        'variant_title',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inv_item_id');
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(WebsiteSetting::class, 'website_setting_id');
    }
}
