<?php

namespace Modules\Courier\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Entities\ShippingPartner;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Courier extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $table = 'deliver_men';

    protected $fillable = [
        'uuid',
        'user_id',
        'shipping_partner_id',
        'status',
        'internal_notes',
        'capacity_max',
    ];

    protected $appends = ['photo'];

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
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getPhotoAttribute()
    {
        $file = $this->getMedia('photo')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingPartner()
    {
        return $this->belongsTo(ShippingPartner::class, 'shipping_partner_id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'deliver_man_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
