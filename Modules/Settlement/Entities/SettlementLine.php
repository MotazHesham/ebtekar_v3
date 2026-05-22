<?php

namespace Modules\Settlement\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Entities\Concerns\HasPrefixedTable;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Support\ShippingTables;

class SettlementLine extends Model
{
    use HasPrefixedTable;

    public $timestamps = false;

    protected static string $shippingTableBase = ShippingTables::COURIER_SETTLEMENT_LINES;

    protected $fillable = [
        'delivery_settlement_id',
        'delivery_order_id',
        'expected_amount',
        'collected_amount',
        'status',
        'created_at',
    ];

    public function settlement()
    {
        return $this->belongsTo(Settlement::class, 'delivery_settlement_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'delivery_order_id');
    }
}
