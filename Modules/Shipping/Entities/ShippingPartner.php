<?php

namespace Modules\Shipping\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shipping\Entities\Concerns\HasPrefixedTable;
use Modules\Shipping\Enums\ShippingPartnerManagementType;
use Modules\Shipping\Support\ShippingTables;

class ShippingPartner extends Model
{
    use HasPrefixedTable;
    use SoftDeletes;

    protected static string $shippingTableBase = ShippingTables::SHIPPING_PARTNERS;

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'user_id',
        'phone',
        'address',
        'is_active',
        'management_type',
        'settings',
        'internal_notes',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'management_type'  => ShippingPartnerManagementType::class,
        'settings'         => 'array',
    ];

    public function skipsPartnerReceiveScan(): bool
    {
        return $this->management_type === ShippingPartnerManagementType::Admin;
    }

    public function scopeRequiresPartnerReceiveScan($query)
    {
        return $query->where('management_type', ShippingPartnerManagementType::Partner->value);
    }

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
