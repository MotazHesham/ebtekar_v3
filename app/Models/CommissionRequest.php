<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionRequest extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'commission_requests';

    protected $dates = [
        'done_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'pending'   => 'Pending',
        'requested' => 'Requested',
        'delivered' => 'Delivered',
    ];

    public const PAYMENT_METHOD_SELECT = [
        'in_company'    => 'in Company',
        'bank_account'  => 'bank Account',
        'vodafon_cache' => 'vodafon Cache',
    ];

    protected $fillable = [
        'status',
        'total_commission',
        'payment_method',
        'transfer_number',
        'done_time',
        'user_id',
        'created_by_id',
        'done_by_user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getDoneTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDoneTimeAttribute($value)
    {
        $this->attributes['done_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function done_by_user()
    {
        return $this->belongsTo(User::class, 'done_by_user_id');
    }
}
