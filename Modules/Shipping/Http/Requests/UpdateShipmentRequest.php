<?php

namespace Modules\Shipping\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Returns\Enums\ReturnReason;
use Modules\Shipping\Enums\ShipmentStatus;
use Modules\Shipping\Support\ShippingTables as ST;

class UpdateShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('delivery_order_edit');
    }

    public function rules(): array
    {
        return [
            'status'              => ['required', Rule::in(ShipmentStatus::values())],
            'shipping_partner_id' => ['nullable', ST::exists(ST::SHIPPING_PARTNERS)],
            'deliver_man_id'      => ['nullable', 'exists:deliver_men,id'],
            'return_reason'       => ['nullable', Rule::in(array_column(ReturnReason::cases(), 'value'))],
            'return_note'         => ['nullable', 'string'],
        ];
    }
}
