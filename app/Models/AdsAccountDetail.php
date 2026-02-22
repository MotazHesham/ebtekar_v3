<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsAccountDetail extends Model
{
    protected $table = 'ads_accounts_details';

    protected $fillable = ['ad_account_id', 'parent_id', 'name', 'utm_key', 'type'];

    public function adsAccount()
    {
        return $this->belongsTo(AdsAccount::class, 'ad_account_id');
    }

    public function parent()
    {
        return $this->belongsTo(AdsAccountDetail::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AdsAccountDetail::class, 'parent_id');
    }
}
