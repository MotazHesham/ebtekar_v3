<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface; 
use Illuminate\Database\Eloquent\Model;  

class ReceiptClientProductPivot extends Model
{
    use Auditable; 
    
    public static $searchable = [
        'description',
    ];

    public $table = 'receipt_client_receipt_client_product';

    protected $dates = [
        'created_at',
        'updated_at', 
    ];

    protected $fillable = [ 
        'description',
        'quantity',
        'price',
        'total_cost',
        'receipt_client_id',
        'receipt_client_product_id',
        'created_at',
        'updated_at', 
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    } 

    public function receipts()
    {
        return $this->belongsTo(ReceiptClient::class,'receipt_client_id');
    }

    public function products()
    {
        return $this->belongsTo(ReceiptClientProduct::class,'receipt_client_product_id');
    }
}
