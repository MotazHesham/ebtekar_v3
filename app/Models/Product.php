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

class Product extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'products';

    public static $searchable = [
        'name',
    ];

    protected $appends = [
        'photos',
        'pdf',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const DISCOUNT_TYPE_SELECT = [
        'flat'    => 'Flat',
        'percent' => 'Percent',
    ];

    protected $fillable = [
        'name',
        'added_by',
        'unit_price',
        'purchase_price',
        'slug',
        'attributes',
        'choice_options',
        'colors',
        'tags',
        'video_provider',
        'video_link',
        'description',
        'discount_type',
        'discount',
        'meta_title',
        'meta_description',
        'flash_deal',
        'published',
        'featured',
        'todays_deal',
        'variant_product',
        'rating',
        'current_stock',
        'user_id',
        'category_id',
        'sub_category_id',
        'sub_sub_category_id',
        'design_id',
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

    public function getPdfAttribute()
    {
        return $this->getMedia('pdf')->last();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function sub_sub_category()
    {
        return $this->belongsTo(SubSubCategory::class, 'sub_sub_category_id');
    }

    public function design()
    {
        return $this->belongsTo(Designe::class, 'design_id');
    }
}
