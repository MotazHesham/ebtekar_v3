<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'alert_text',
        'alert_link',
        'type',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
