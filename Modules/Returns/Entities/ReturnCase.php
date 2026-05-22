<?php

namespace Modules\Returns\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Courier\Entities\Courier;
use Modules\Returns\Enums\ReturnCaseStatus;
use Modules\Returns\Enums\ReturnReason;
use Modules\Shipping\Entities\Concerns\HasPrefixedTable;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Entities\ShippingPartner;
use Modules\Shipping\Support\ShippingTables;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ReturnCase extends Model implements HasMedia
{
    use HasPrefixedTable;
    use InteractsWithMedia;

    protected static string $shippingTableBase = ShippingTables::RETURN_CASES;

    protected $fillable = [
        'uuid',
        'delivery_order_id',
        'deliver_man_id',
        'shipping_partner_id',
        'created_by_user_id',
        'reason',
        'note',
        'shipment_status',
        'status',
        'warehouse_received_at',
        'closed_at',
    ];

    protected $casts = [
        'warehouse_received_at' => 'datetime',
        'closed_at'             => 'datetime',
    ];

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

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(120)->height(120);
        $this->addMediaConversion('preview')->width(400)->height(400);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'delivery_order_id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'deliver_man_id')->withTrashed();
    }

    public function shippingPartner()
    {
        return $this->belongsTo(ShippingPartner::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id')->withTrashed();
    }

    public function getReasonLabelAttribute(): string
    {
        $enum = ReturnReason::tryFrom($this->reason);

        return $enum?->label() ?? $this->reason;
    }

    public function getStatusLabelAttribute(): string
    {
        $key = 'returns::case_status.' . $this->status;

        return trans()->has($key) ? __($key) : $this->status;
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

    public function isOpen(): bool
    {
        return $this->status === ReturnCaseStatus::Open->value;
    }
}
