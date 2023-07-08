<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mockup extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'mockups'; 

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'preview_1',
        'preview_2',
        'preview_3',
        'description',
        'video_provider',
        'video_link',
        'purchase_price',
        'attributes',
        'attribute_options',
        'colors',
        'category_id',
        'sub_category_id',
        'sub_sub_category_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function stocks()
    {
        return $this->hasMany(MockupStock::class, 'mockup_id');
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
}
