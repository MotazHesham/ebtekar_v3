<?php

namespace Modules\Notifications\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Entities\Concerns\HasPrefixedTable;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Support\ShippingTables;

class NotificationDelivery extends Model
{
    use HasPrefixedTable;

    public $timestamps = false;

    protected static string $shippingTableBase = ShippingTables::NOTIFICATION_DELIVERIES;

    protected $fillable = [
        'channel',
        'event_type',
        'delivery_order_id',
        'user_id',
        'title',
        'body',
        'status',
        'meta',
        'sent_at',
        'created_at',
    ];

    protected $casts = [
        'meta'    => 'array',
        'sent_at' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'delivery_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        $key = 'notifications::delivery_status.' . $this->status;

        return trans()->has($key) ? __($key) : $this->status;
    }
}
