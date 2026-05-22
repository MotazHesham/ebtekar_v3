<?php

namespace Modules\Shipping\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Shipping\Support\ShippingTables as ST;

class MassDestroyShippingPartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('shipping_partner_delete');
    }

    public function rules(): array
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => ST::exists(ST::SHIPPING_PARTNERS),
        ];
    }
}
