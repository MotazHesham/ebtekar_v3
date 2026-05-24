<?php

namespace Modules\Shipping\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Returns\Enums\ReturnReason;
use Modules\Shipping\Enums\ShipmentStatus;
use Modules\Shipping\Support\ShippingTables as ST;

class QuickShipmentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipment_id'    => ['required', 'integer', ST::exists(ST::DELIVERY_ORDERS)],
            'action'         => ['required', Rule::in([
                'delivered',
                'returned',
                'revert_handoff',
                'cancel_delivered',
                'cancel_return',
            ])],
            'return_reason'  => ['nullable', Rule::in(array_column(ReturnReason::cases(), 'value'))],
            'note'           => ['nullable', 'string', 'max:1000'],
        ];
    }
}
