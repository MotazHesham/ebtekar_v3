<?php

namespace Modules\Inventory\Entities;

use App\Models\ReceiptSocialProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventorySocialLink extends Model
{
    protected $table = 'inv_social_links';

    protected $fillable = [
        'inv_item_id',
        'receipt_social_product_id',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inv_item_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(ReceiptSocialProduct::class, 'receipt_social_product_id')->withTrashed();
    }
}
