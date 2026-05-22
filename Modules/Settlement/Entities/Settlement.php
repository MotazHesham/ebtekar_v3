<?php

namespace Modules\Settlement\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Courier\Entities\Courier;

class Settlement extends Model
{
    protected $table = 'delivery_settlements';

    public const STATUS_SELECT = [
        'pending'   => 'Pending',
        'confirmed' => 'Confirmed',
    ];

    protected $casts = [
        'settlement_date' => 'date',
    ];

    protected $fillable = [
        'deliver_man_id',
        'settled_by_user_id',
        'settlement_date',
        'expected_amount',
        'collected_amount',
        'difference_amount',
        'status',
        'notes',
    ];

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'deliver_man_id')->withTrashed();
    }

    public function settledBy()
    {
        return $this->belongsTo(User::class, 'settled_by_user_id')->withTrashed();
    }
}
