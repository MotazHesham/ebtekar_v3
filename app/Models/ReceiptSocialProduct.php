<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ReceiptSocialProduct extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    protected $appends = [
        'photos',
    ];

    public static $searchable = [
        'name',
    ];

    public $table = 'receipt_social_products';

    public const PRODUCT_TYPE_SELECT = [
        'season' => 'منتج سيزون', 
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'product_type',
        'name',
        'price',
        'commission',
        'shopify_id',
        'website_setting_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getPhotosAttribute()
    {
        $files = $this->getMedia('photos');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }

    public function receipts()
    {
        return $this->belongsToMany(ReceiptSocial::class);
    }
    
    public function website(){
        return $this->belongsTo(WebsiteSetting::class,'website_setting_id');
    } 
    
    public function receiptProducts()
    {
        return $this->hasMany(ReceiptSocialProductPivot::class,'receipt_social_product_id');
    }
}
