<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class DeviceUserToken extends Model
{
    use Auditable, HasFactory;

    public $table = 'user_device_tokens';

    protected $dates = [
        'created_at',
        'updated_at', 
    ];

    protected $fillable = [
        'device_token',
        'user_id',
        'created_at',
        'updated_at', 
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
