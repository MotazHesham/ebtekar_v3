<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsPaymentRequest extends Model
{
    protected $table = 'ads_payments_requests';

    public $timestamps = false;

    protected $fillable = ['ad_account_id', 'code', 'amount', 'status', 'add_date', 'transaction_reference', 'receipt', 'from_ad_account_id', 'to_ad_account_id', 'reason', 'transaction_type'];

    protected $casts = [
        'add_date' => 'datetime',
        'amount'   => 'decimal:2',
    ];

    public function adsAccount()
    {
        return $this->belongsTo(AdsAccount::class, 'ad_account_id');
    }

    public function fromAdsAccount()
    {
        return $this->belongsTo(AdsAccount::class, 'from_ad_account_id');
    }

    public function toAdsAccount()
    {
        return $this->belongsTo(AdsAccount::class, 'to_ad_account_id');
    }

    public function isTransfer()
    {
        return $this->transaction_type === 'transfer' || (!is_null($this->from_ad_account_id) && !is_null($this->to_ad_account_id));
    }
}
