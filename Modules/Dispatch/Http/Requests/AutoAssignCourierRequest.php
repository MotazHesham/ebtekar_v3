<?php

namespace Modules\Dispatch\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Shipping\Support\ShippingTables as ST;

class AutoAssignCourierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('delivery_assign_courier');
    }

    public function rules(): array
    {
        return [
            'shipment_ids'        => ['required', 'array', 'min:1'],
            'shipment_ids.*'      => ['integer', ST::exists(ST::DELIVERY_ORDERS)],
            'shipping_partner_id' => ['nullable', 'integer', ST::exists(ST::SHIPPING_PARTNERS)],
        ];
    }
}
