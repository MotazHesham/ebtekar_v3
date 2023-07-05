<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use DateTimeInterface;

class OrderDetail extends Model
{
    public $table = 'order_details';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'order_id',
        'product_id',
        'variation',
        'commission', 
        'extra_commission', 
        'total_cost', 
        'quantity', 
        'price', 
        'weight_price', 
        'photos',  
        'link', 
        'pdf', 
        'email_sent', 
        'pdf', 
        'description',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    } 

    //calculation 
    public function calc_price($exchange_rate){
        return exchange_rate($this->price,$exchange_rate) + exchange_rate($this->weight_price,$exchange_rate);
    }
    public function total_cost($exchange_rate){
        return exchange_rate($this->total_cost,$exchange_rate);
    }
}
