<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserAlert extends Model
{
    use HasFactory;

    public $table = 'user_alerts';

    
    public const TYPE_SELECT = [
        'private'     => 'users notifiacations ',
        'history'        => 'assign the transfer between stages in playlist to the user.... to be in his history list',
        'playlist' => 'This is for tracking playlist by admin', 
        'public' => 'when the admin need send notification to specfic users or all',
        'orders' => 'for orders get from websites',
        'request_commission' => 'for request_commissions',
        'design' => 'for added new design from designer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'alert_text',
        'alert_link',
        'data',
        'type',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
