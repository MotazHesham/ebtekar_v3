<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralVisit extends Model
{
    use HasFactory;

    public $table = 'referral_visits';

    protected $fillable = [
        'marketer_id',
        'website_setting_id',
        'ref_code',
        'cookie_id',
        'session_id',
        'ip',
        'device',
        'browser',
        'user_agent',
        'landing_url',
        'utm_source',
        'utm_campaign',
        'first_seen_at',
        'last_seen_at',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
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
