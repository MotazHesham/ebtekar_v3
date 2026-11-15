<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $table = 'inv_items';

    protected $fillable = [
        'name',
        'sku',
    ];

    public function balance(): HasOne
    {
        return $this->hasOne(InventoryBalance::class, 'inv_item_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'inv_item_id');
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(InventorySocialLink::class, 'inv_item_id');
    }

    public function channelLinks(): HasMany
    {
        return $this->hasMany(InventoryChannelLink::class, 'inv_item_id');
    }
}
