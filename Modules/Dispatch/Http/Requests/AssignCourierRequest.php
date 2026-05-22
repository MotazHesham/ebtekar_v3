<?php

namespace Modules\Dispatch\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Shipping\Support\ShippingTables as ST;

class AssignCourierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('delivery_assign_courier');
    }

    public function rules(): array
    {
        return [
            'shipment_id' => ['required', 'integer', ST::exists(ST::DELIVERY_ORDERS)],
            'courier_id'  => ['required', 'integer', 'exists:deliver_men,id'],
        ];
    }
}
