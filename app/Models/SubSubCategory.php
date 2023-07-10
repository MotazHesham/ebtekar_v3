<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubSubCategory extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'sub_sub_categories';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'slug',
        'design',
        'meta_title',
        'meta_description',
        'sub_category_id',
        'website_setting_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id')->withTrashed();
    }
    public function website(){
        return $this->belongsTo(WebsiteSetting::class,'website_setting_id');
    }
}
