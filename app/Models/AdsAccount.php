<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdsAccount extends Model
{
    protected $fillable = ['name', 'balance', 'type'];

    public const TYPE_SELECT = [
        'normal' => 'Normal',
        'messages' => 'Messages',
    ];
    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(AdsAccountDetail::class, 'ad_account_id');
    }

    public function paymentRequests()
    {
        return $this->hasMany(AdsPaymentRequest::class, 'ad_account_id');
    }

    public function history()
    {
        return $this->hasMany(AdsAccountHistory::class, 'ad_id');
    }
}
