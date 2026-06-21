<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    use HasFactory;

    public $table = 'phone_verifications';

    protected $dates = [
        'expires_at',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'application',
        'scope',
        'phone',
        'otp_code',
        'application',
        'expires_at',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
