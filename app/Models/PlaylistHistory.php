<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistHistory extends Model
{
    use HasFactory;

    protected $table = 'playlist_histories';

    protected $fillable = [
        'model_type',
        'model_id',
        'action_type',
        'from_status',
        'to_status',
        'is_return',
        'reason',
        'user_id',
        'assigned_to_user_id',
        'assignment_type',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_return' => 'boolean',
    ];

    protected $dates = [
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedToUser()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }
}

