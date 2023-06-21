<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptClientProduct extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public static $searchable = [
        'name',
    ];

    public $table = 'receipt_client_products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'price',
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
        return $this->hasMany(ReceiptClientProductPivot::class, 'receipt_client_product_id');
    }
}
