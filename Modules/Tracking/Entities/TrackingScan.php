<?php

namespace Modules\Tracking\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Entities\Concerns\HasPrefixedTable;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Entities\ShippingPartner;
use Modules\Shipping\Support\ShippingTables;

class TrackingScan extends Model
{
    use HasPrefixedTable;

    protected static string $shippingTableBase = ShippingTables::TRACKING_SCANS;

    public $timestamps = false;

    protected $fillable = [
        'delivery_order_id',
        'shipping_partner_id',
        'user_id',
        'scan_type',
        'barcode',
        'result',
        'message',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'delivery_order_id');
    }

    public function shippingPartner()
    {
        return $this->belongsTo(ShippingPartner::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
