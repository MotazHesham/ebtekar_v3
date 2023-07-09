<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Police extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'polices';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    
    public const NAME_SELECT = [
        'support_policy'     => 'سياسة الدعم',
        'return_policy'   => 'سياسة المرتجعات',
        'seller_policy' => 'سياسة البائع', 
        'terms' => 'سياسة البنود', 
        'privacy_policy' => 'سياسة الخصوصية', 
    ];

    protected $fillable = [
        'name',
        'content',
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
}
