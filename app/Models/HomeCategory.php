<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class HomeCategory extends Model
{
    use  HasFactory;

    public $table = 'home_categories';

    protected $dates = [
        'created_at',
        'updated_at', 
    ];

    protected $fillable = [
        'category_id',
        'website_setting_id',
        'created_at',
        'updated_at', 
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function website(){
        return $this->belongsTo(WebsiteSetting::class,'website_setting_id');
    }
}
