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
use Illuminate\Support\Str;

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
        'object_3d',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const WEIGHT_SELECT = [
        'half_kg'     => '0.5 KG',
        'one_kg'      => '1.0 KG',
        'one_half_kg' => '1.5 KG',
        'two_kg'      => '2.0 KG',
        'two_half_kg' => '2.5 KG',
        'three_kg'    => '3.0 KG',
    ];

    public const DISCOUNT_TYPE_SELECT = [
        'flat'    => 'Flat',
        'percent' => 'Percent',
    ];

    public const SPECIAL_SELECT = [
        1 => 'Yes',
        0 => 'No',
    ];

    public const VIDEO_PROVIDER_SELECT = [
        'youtube'    => 'Youtube',
        'dailymotion' => 'Dailymotion',
        'vimeo' => 'Vimeo',
    ];

    protected $fillable = [
        'name',
        'weight',
        'added_by',
        'unit_price',
        'purchase_price',
        'slug',
        'attributes',
        'attribute_options',
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
        'special',
        'require_photos',
        'variant_product',
        'rating',
        'current_stock',
        'user_id',
        'category_id',
        'sub_category_id',
        'sub_sub_category_id',
        'design_id',
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
        $this->addMediaConversion('thumb')->fit('crop', 84, 108);
        $this->addMediaConversion('preview')->fit('crop', 123, 123);
        $this->addMediaConversion('preview2')->fit('crop', 203, 203); 
        $this->addMediaConversion('preview3')->fit('crop', 254, 254); 
    }

    public function getPhotosAttribute()
    {
        $files = $this->getMedia('photos');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
            $item->preview2   = $item->getUrl('preview2');
            $item->preview3   = $item->getUrl('preview3');
        });

        return $files;
    }

    public function getObject3dAttribute()
    {
        return $this->getMedia('object_3d')->last();
    }
    
    public function getPdfAttribute()
    {
        return $this->getMedia('pdf')->last();
    }

    public function duplicate(){
        
        $newProduct = $this->replicate();
        $newProduct->slug = Str::slug($this->name, '-',null) . '-' . Str::random(7);
        $newProduct->save();

        foreach($this->stocks as $stock){
            $newStock = $stock->replicate();
            $newStock->product_id = $newProduct->id;
            $newStock->save();
        }
        
        return $newProduct;
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->withTrashed();
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id')->withTrashed();
    }

    public function sub_sub_category()
    {
        return $this->belongsTo(SubSubCategory::class, 'sub_sub_category_id')->withTrashed();
    }

    public function design()
    {
        return $this->belongsTo(Design::class, 'design_id');
    }
    
    public function website(){
        return $this->belongsTo(WebsiteSetting::class,'website_setting_id');
    }
    //operations
    public function calc_discount($unit_price)
    {
        if ($this->discount > 0) {
            if ($this->discount_type == 'flat') {
                return $unit_price - $this->discount;
            } elseif ($this->discount_type == 'percent') {
                $amount = ($unit_price / 100) * $this->discount;
                return $unit_price - $amount;
            }
        }else{
            return $unit_price;
        }
    }

    
    public function calc_price_as_text(){
        $price = '';
        if($this->discount > 0){
            $price .= front_calc_product_currency($this->calc_discount($this->unit_price),$this->weight)['as_text'];
            $price .= ' <span>' . front_calc_product_currency($this->unit_price,$this->weight)['as_text'] . '</span>';
        }else{
            $price .= front_calc_product_currency($this->unit_price,$this->weight)['as_text'];
        } 
        return $price;
    } 
}
