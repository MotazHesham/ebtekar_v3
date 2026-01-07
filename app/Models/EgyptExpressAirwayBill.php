<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EgyptExpressAirwayBill extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'egyptexpress_airway_bills';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'model_type',
        'model_id',
        'shipper_reference',
        'order_num',
        'airway_bill_number',
        'tracking_number',
        'status',
        'status_description',
        'receiver_name',
        'receiver_phone',
        'receiver_city',
        'destination',
        'number_of_pieces',
        'weight',
        'goods_description',
        'cod_amount',
        'cod_currency',
        'invoice_value',
        'invoice_currency',
        'request_payload',
        'response_data',
        'is_successful',
        'error_message',
        'http_status_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_data' => 'array',
        'is_successful' => 'boolean',
        'number_of_pieces' => 'integer',
        'weight' => 'decimal:2',
        'cod_amount' => 'decimal:2',
        'invoice_value' => 'decimal:2',
        'http_status_code' => 'integer',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Get the parent model (polymorphic relationship)
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Scope to get successful airway bills
     */
    public function scopeSuccessful($query)
    {
        return $query->where('is_successful', true);
    }

    /**
     * Scope to get failed airway bills
     */
    public function scopeFailed($query)
    {
        return $query->where('is_successful', false);
    }

    /**
     * Scope to find by tracking number
     */
    public function scopeByTrackingNumber($query, string $trackingNumber)
    {
        return $query->where('tracking_number', $trackingNumber)
            ->orWhere('airway_bill_number', $trackingNumber);
    }

    /**
     * Scope to find by shipper reference
     */
    public function scopeByShipperReference($query, string $reference)
    {
        return $query->where('shipper_reference', $reference);
    }
}
