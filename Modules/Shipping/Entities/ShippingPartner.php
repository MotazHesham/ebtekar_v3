<?php

namespace Modules\Shipping\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingPartner extends Model
{
    use SoftDeletes;

    protected $table = 'shipping_partners';

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'user_id',
        'phone',
        'address',
        'is_active',
        'settings',
        'internal_notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings'  => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'shipping_partner_id');
    }

    public function couriers()
    {
        return $this->hasMany(\Modules\Courier\Entities\Courier::class, 'shipping_partner_id');
    }

    /** @deprecated Use couriers() */
    public function deliverMen()
    {
        return $this->couriers();
    }

    /** @deprecated Use shipments() */
    public function deliveryOrders()
    {
        return $this->shipments();
    }
}
