<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface; 
use Illuminate\Database\Eloquent\Model;  

class ReceiptSocialProductPivot extends Model
{
    use Auditable; 
    
    public static $searchable = [
        'title',
        'description',
    ];

    public $table = 'receipt_social_receipt_social_product';

    protected $dates = [
        'created_at',
        'updated_at', 
    ];

    public const TYPE_SELECT = [
        'single' => 'فردي',
        'box' => 'بوكس',
    ];

    protected $fillable = [
        'type',
        'title',
        'description',
        'quantity',
        'price',
        'total_cost',
        'commission',
        'extra_commission',
        'pdf',
        'photos',
        'receipt_social_product_id',
        'receipt_social_id',
        'shopify_id',
        'created_at',
        'updated_at', 
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    } 

    public function receipt()
    {
        return $this->belongsTo(ReceiptSocial::class,'receipt_social_id');
    }

    public function products()
    {
        return $this->belongsTo(ReceiptSocialProduct::class,'receipt_social_product_id')->withTrashed();
    }

    public function boxDetails()
    {
        return $this->hasMany(ReceiptSocialBoxDetail::class, 'receipt_social_product_pivot_id');
    }
}
