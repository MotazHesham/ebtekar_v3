<?php

namespace Modules\Timeline\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shipping\Entities\Concerns\HasPrefixedTable;
use Modules\Shipping\Support\ShippingTables;

class ShipmentNote extends Model
{
    use HasPrefixedTable;
    use SoftDeletes;

    protected static string $shippingTableBase = ShippingTables::DELIVERY_NOTES;

    protected $fillable = [
        'delivery_order_id',
        'user_id',
        'parent_id',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at');
    }
}
