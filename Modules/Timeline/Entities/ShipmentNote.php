<?php

namespace Modules\Timeline\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentNote extends Model
{
    use SoftDeletes;

    protected $table = 'delivery_notes';

    protected $fillable = [
        'delivery_order_id',
        'user_id',
        'parent_id',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at');
    }
}
