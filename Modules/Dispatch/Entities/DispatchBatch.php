<?php

namespace Modules\Dispatch\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Courier\Entities\Courier;
use Modules\Shipping\Entities\Concerns\HasPrefixedTable;
use Modules\Shipping\Entities\ShippingPartner;
use Modules\Shipping\Support\ShippingTables;

class DispatchBatch extends Model
{
    use HasPrefixedTable;

    protected static string $shippingTableBase = ShippingTables::DISPATCH_BATCHES;

    protected $fillable = [
        'type',
        'status',
        'shipping_partner_id',
        'courier_id',
        'created_by_user_id',
        'total_count',
        'success_count',
        'failed_count',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(DispatchBatchItem::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    public function shippingPartner()
    {
        return $this->belongsTo(ShippingPartner::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
