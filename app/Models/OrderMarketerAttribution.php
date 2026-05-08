<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMarketerAttribution extends Model
{
    use HasFactory;

    public $table = 'order_marketer_attributions';

    protected $fillable = [
        'order_id',
        'marketer_id',
        'customer_marketer_attribution_id',
        'source',
        'commission_status',
        'commission_base',
        'commission_rate',
        'commission_amount',
        'approved_at',
        'paid_at',
        'credited_at',
        'rejected_reason',
    ];

    protected $casts = [
        'commission_base' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'credited_at' => 'datetime',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function marketer()
    {
        return $this->belongsTo(Marketer::class, 'marketer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
