<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptPriceViewProduct extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'receipt_price_view_products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'description',
        'price',
        'quantity',
        'total_cost',
        'receipt_price_view_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function receipt_price_view()
    {
        return $this->belongsTo(ReceiptPriceView::class, 'receipt_price_view_id');
    }
}
