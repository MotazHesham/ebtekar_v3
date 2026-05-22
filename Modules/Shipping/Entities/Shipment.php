<?php

namespace Modules\Shipping\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Courier\Entities\Courier;
use Modules\Shipping\Enums\ShipmentStatus;

class Shipment extends Model
{
    use SoftDeletes;

    protected $table = 'delivery_orders';

    protected $fillable = [
        'uuid',
        'orderable_type',
        'orderable_id',
        'shipping_partner_id',
        'deliver_man_id',
        'assigned_by_user_id',
        'status',
        'return_reason',
        'return_note',
        'order_num',
        'client_name',
        'phone_number',
        'governorate',
        'region',
        'cod_amount',
        'deposit_amount',
        'remaining_cod',
        'shipping_cost',
        'payment_status',
        'last_status_at',
        'handed_to_partner_at',
        'received_by_partner_at',
        'out_with_courier_at',
        'first_attempt_at',
        'delivered_at',
        'returned_at',
        'returned_to_warehouse_at',
        'settled_at',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'last_status_at' => 'datetime',
        'handed_to_partner_at' => 'datetime',
        'received_by_partner_at' => 'datetime',
        'out_with_courier_at' => 'datetime',
        'first_attempt_at' => 'datetime',
        'delivered_at' => 'datetime',
        'returned_at' => 'datetime',
        'returned_to_warehouse_at' => 'datetime',
        'settled_at' => 'datetime',
    ];

    protected $appends = ['pending_since'];

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? (str_contains((string) $value, '-') ? 'uuid' : 'id');

        return $this->where($field, $value)->firstOrFail();
    }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function orderable()
    {
        return $this->morphTo();
    }

    public function shippingPartner()
    {
        return $this->belongsTo(ShippingPartner::class)->withTrashed();
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'deliver_man_id')->withTrashed();
    }

    public function timelineEvents()
    {
        return $this->hasMany(\Modules\Timeline\Entities\TimelineEvent::class, 'delivery_order_id')
            ->orderByDesc('created_at');
    }

    public function notes()
    {
        return $this->hasMany(\Modules\Timeline\Entities\ShipmentNote::class, 'delivery_order_id')
            ->whereNull('parent_id')
            ->orderByDesc('created_at');
    }

    /** @deprecated Use courier() */
    public function deliverMan()
    {
        return $this->courier();
    }

    public function getStatusEnum(): ShipmentStatus
    {
        return ShipmentStatus::from($this->status);
    }

    public function getStatusLabelAttribute(): string
    {
        $key = 'delivery_order_status.' . $this->status;

        return trans()->has($key) ? __($key) : $this->status;
    }

    public function getPendingSinceAttribute(): ?string
    {
        if (! $this->last_status_at) {
            return null;
        }

        return Carbon::parse($this->last_status_at)->diffForHumans();
    }

    public function scopeForUser($query, $user = null)
    {
        $user = $user ?: auth()->user();
        if (! $user || $user->is_admin) {
            return $query;
        }

        if ($user->user_type === 'shipping_partner') {
            $partnerId = ShippingPartner::where('user_id', $user->id)->value('id');

            return $partnerId ? $query->where('shipping_partner_id', $partnerId) : $query->whereRaw('0=1');
        }

        if (in_array($user->user_type, ['courier', 'delivery_man'], true)) {
            $courierId = Courier::where('user_id', $user->id)->value('id');

            return $courierId ? $query->where('deliver_man_id', $courierId) : $query->whereRaw('0=1');
        }

        return $query;
    }
}
