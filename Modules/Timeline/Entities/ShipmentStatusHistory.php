<?php

namespace Modules\Timeline\Entities;

use Illuminate\Database\Eloquent\Model;

class ShipmentStatusHistory extends Model
{
    protected $table = 'shipment_status_histories';

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
