<?php

namespace Modules\Inventory\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    protected $table = 'inv_movements';

    protected $fillable = [
        'inv_item_id',
        'quantity_delta',
        'on_hand_after',
        'type',
        'source_type',
        'source_id',
        'note',
        'user_id',
    ];

    protected $casts = [
        'quantity_delta' => 'integer',
        'on_hand_after' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inv_item_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
