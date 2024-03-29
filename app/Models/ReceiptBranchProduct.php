<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptBranchProduct extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public static $searchable = [
        'name',
    ];

    public $table = 'receipt_branch_products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'price',
        'price_parts',
        'price_permissions',
        'website_setting_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function receipts()
    {
        return $this->hasMany(ReceiptBranchProductPivot::class, 'receipt_branch_product_id');
    }
    public function website(){
        return $this->belongsTo(WebsiteSetting::class,'website_setting_id');
    }
}
