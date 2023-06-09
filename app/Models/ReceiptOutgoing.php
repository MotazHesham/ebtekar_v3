<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptOutgoing extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'receipt_outgoings';

    public static $searchable = [
        'order_num',
        'client_name',
        'phone_number',
    ];

    protected $dates = [
        'date_of_receiving_order',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'order_num',
        'date_of_receiving_order',
        'client_name',
        'phone_number',
        'total_cost',
        'note',
        'done',
        'printing_times',
        'staff_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function receiptOutgoingReceiptOutgoingProducts()
    {
        return $this->hasMany(ReceiptOutgoingProduct::class, 'receipt_outgoing_id', 'id');
    }

    public function getDateOfReceivingOrderAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateOfReceivingOrderAttribute($value)
    {
        $this->attributes['date_of_receiving_order'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
