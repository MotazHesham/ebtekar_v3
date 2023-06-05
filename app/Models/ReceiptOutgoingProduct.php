<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptOutgoingProduct extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'receipt_outgoing_products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'description',
        'price',
        'quantity',
        'total_cost',
        'receipt_outgoing_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function receipt_outgoing()
    {
        return $this->belongsTo(ReceiptOutgoing::class, 'receipt_outgoing_id');
    }
}
