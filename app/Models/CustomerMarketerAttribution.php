<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMarketerAttribution extends Model
{
    use HasFactory;

    public $table = 'customer_marketer_attributions';

    protected $fillable = [
        'marketer_id',
        'website_setting_id',
        'customer_identifier',
        'source',
        'priority_rule_snapshot',
        'assigned_at',
        'expires_at',
        'is_locked',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_locked' => 'boolean',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function marketer()
    {
        return $this->belongsTo(Marketer::class, 'marketer_id');
    }
}
