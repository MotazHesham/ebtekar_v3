<?php

namespace Modules\Dispatch\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Courier\Entities\Courier;
use Modules\Shipping\Entities\Concerns\HasPrefixedTable;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Support\ShippingTables;

class DispatchBatchItem extends Model
{
    use HasPrefixedTable;

    public $timestamps = false;

    protected static string $shippingTableBase = ShippingTables::DISPATCH_BATCH_ITEMS;

    protected $fillable = [
        'dispatch_batch_id',
        'delivery_order_id',
        'courier_id',
        'result',
        'message',
        'created_at',
    ];

    public function batch()
    {
        return $this->belongsTo(DispatchBatch::class, 'dispatch_batch_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'delivery_order_id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }
}
