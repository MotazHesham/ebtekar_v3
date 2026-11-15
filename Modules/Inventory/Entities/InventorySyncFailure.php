<?php

namespace Modules\Inventory\Entities;

use App\Models\WebsiteSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventorySyncFailure extends Model
{
    protected $table = 'inv_sync_failures';

    protected $fillable = [
        'inv_item_id',
        'inv_channel_link_id',
        'website_setting_id',
        'quantity_sent',
        'error',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'quantity_sent' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inv_item_id');
    }

    public function channelLink(): BelongsTo
    {
        return $this->belongsTo(InventoryChannelLink::class, 'inv_channel_link_id');
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(WebsiteSetting::class, 'website_setting_id');
    }
}
