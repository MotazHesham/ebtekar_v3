<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class ReceiptSocialBoxDetail extends Model
{
    use Auditable;

    public $table = 'receipt_social_box_details';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'receipt_social_product_pivot_id',
        'receipt_social_product_id',
        'quantity',
        'price',
        'total_cost',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function pivot()
    {
        return $this->belongsTo(ReceiptSocialProductPivot::class, 'receipt_social_product_pivot_id');
    }

    public function product()
    {
        return $this->belongsTo(ReceiptSocialProduct::class, 'receipt_social_product_id')->withTrashed();
    }
}

