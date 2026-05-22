<?php

namespace Modules\Timeline\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Entities\Concerns\HasPrefixedTable;
use Modules\Shipping\Support\ShippingTables;

class ShipmentStatusHistory extends Model
{
    use HasPrefixedTable;

    public $timestamps = false;

    protected static string $shippingTableBase = ShippingTables::SHIPMENT_STATUS_HISTORIES;

    protected $fillable = [
        'delivery_order_id',
        'user_id',
        'old_status',
        'new_status',
        'duration_seconds',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
