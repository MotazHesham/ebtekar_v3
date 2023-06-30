<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class Cart extends Model 
{
    use  HasFactory;

    public $table = 'carts';  

    protected $dates = [
        'created_at',
        'updated_at', 
    ];

    protected $fillable = [
        'variation',
        'description',
        'quantity',
        'price',
        'total_cost',
        'commission',
        'photos',
        'pdf',
        'link',
        'email_sent',
        'product_id',
        'user_id',  
        'created_at',
        'updated_at', 
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    } 

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
