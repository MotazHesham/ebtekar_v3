<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryBalance extends Model
{
    protected $table = 'inv_balances';

    protected $fillable = [
        'inv_item_id',
        'on_hand',
        'sync_pending',
    ];

    protected $casts = [
        'on_hand' => 'integer',
        'sync_pending' => 'boolean',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inv_item_id');
    }
}
