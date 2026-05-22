<?php

namespace Modules\Timeline\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TimelineEvent extends Model
{
    public $table = 'delivery_timeline_events';

    public $timestamps = false;

    public const TYPE_CREATED        = 'created';
    public const TYPE_STATUS_CHANGE  = 'status_change';
    public const TYPE_NOTE_ADDED     = 'note_added';
    public const TYPE_ASSIGNED       = 'assigned';
    public const TYPE_SCAN_HANDOFF   = 'scan_handoff';
    public const TYPE_SCAN_RECEIVE   = 'scan_receive';

    protected $fillable = [
        'delivery_order_id',
        'user_id',
        'event_type',
        'old_status',
        'new_status',
        'body',
        'meta',
        'created_at',
    ];

    protected $casts = ['meta' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
