<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use App\Traits\Auditable;
use DateTimeInterface;

class OrderDetail extends Model
{
    use Auditable;

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
        return $this->belongsTo(Product::class)->withTrashed();
    } 

    //calculation 
    public function calc_price($exchange_rate){
        return exchange_rate($this->price,$exchange_rate) + exchange_rate($this->weight_price,$exchange_rate);
    }
    public function total_cost($exchange_rate){
        return exchange_rate($this->total_cost,$exchange_rate);
    }
    public function calc_commission($exchange_rate){
        return exchange_rate($this->commission + $this->extra_commission,$exchange_rate);
    }
}
