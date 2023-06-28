<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStock extends Model
{
    public $table = 'product_stocks';
    
    protected $dates = [
        'created_at',
        'updated_at', 
    ];


    protected $fillable = [
        'product_id',
        'variant', 
        'stock',
        'unit_price',
        'purchase_price',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
