<?php

namespace Modules\Settlement\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Courier\Entities\Courier;
use Modules\Settlement\Enums\SettlementStatus;
use Modules\Shipping\Entities\Concerns\HasPrefixedTable;
use Modules\Shipping\Support\ShippingTables;

class Settlement extends Model
{
    use HasPrefixedTable;

    protected static string $shippingTableBase = ShippingTables::DELIVERY_SETTLEMENTS;

    protected $casts = [
        'settlement_date'   => 'date',
        'expected_amount'   => 'decimal:2',
        'collected_amount'  => 'decimal:2',
        'difference_amount' => 'decimal:2',
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

    public function lines()
    {
        return $this->hasMany(SettlementLine::class, 'delivery_settlement_id');
    }

    public function getStatusLabelAttribute(): string
    {
        $key = 'settlement::status.' . $this->status;

        return trans()->has($key) ? __($key) : $this->status;
    }

    public function scopeForUser($query, $user = null)
    {
        $user = $user ?: auth()->user();
        if (! $user || $user->is_admin) {
            return $query;
        }

        if (in_array($user->user_type, ['courier', 'delivery_man'], true)) {
            $courierId = Courier::where('user_id', $user->id)->value('id');

            return $courierId ? $query->where('deliver_man_id', $courierId) : $query->whereRaw('0=1');
        }

        return $query;
    }
}
