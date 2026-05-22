<?php

namespace Modules\Returns\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Shipping\Support\ShippingTables as ST;
use Illuminate\Validation\Rule;
use Modules\Returns\Enums\ReturnReason;
use Modules\Shipping\Enums\ShipmentStatus;

class StoreReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('delivery_return_access');
    }

    public function rules(): array
    {
        return [
            'shipment_id'     => ['required', 'integer', ST::exists(ST::DELIVERY_ORDERS)],
            'reason'          => ['required', Rule::in(array_column(ReturnReason::cases(), 'value'))],
            'note'            => ['nullable', 'string', 'max:2000'],
            'shipment_status' => ['nullable', Rule::in([ShipmentStatus::Returned->value, ShipmentStatus::Refused->value])],
        ];
    }
}
