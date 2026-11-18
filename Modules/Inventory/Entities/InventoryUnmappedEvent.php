<?php

namespace Modules\Inventory\Entities;

use App\Models\WebsiteSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryUnmappedEvent extends Model
{
    protected $table = 'inv_unmapped_events';

    protected $fillable = [
        'website_setting_id',
        'shopify_variant_id',
        'source_type',
        'source_id',
        'receipt_social_id',
        'receipt_social_product_id',
        'product_name',
        'message',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(WebsiteSetting::class, 'website_setting_id');
    }
}
