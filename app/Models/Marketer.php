<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketer extends Model
{
    use Auditable, HasFactory;

    public $table = 'marketers';

    protected $fillable = [
        'user_id',
        'website_setting_id',
        'name',
        'code',
        'commission_rate',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function website()
    {
        return $this->belongsTo(WebsiteSetting::class, 'website_setting_id');
    }

    public function orderAttributions()
    {
        return $this->hasMany(OrderMarketerAttribution::class, 'marketer_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(MarketerWalletTransaction::class, 'marketer_id');
    }
}
