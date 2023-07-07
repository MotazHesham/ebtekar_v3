<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class CommissionRequestOrders extends Model
{
    protected $table = 'commission_request_orders';

    protected $dates = [ 
        'created_at',
        'updated_at', 
    ];

    protected $fillable = [
        'commission_request_id',
        'order_id',
        'commission', 
        'created_at',
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

}
