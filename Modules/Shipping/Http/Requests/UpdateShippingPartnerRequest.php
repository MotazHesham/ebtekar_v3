<?php

namespace Modules\Shipping\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Shipping\Enums\ShippingPartnerManagementType;
use Modules\Shipping\Support\ShippingTables as ST;

class UpdateShippingPartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('shipping_partner_edit');
    }

    public function rules(): array
    {
        $partner = $this->route('shipping_partner');

        return [
            'name'           => ['required', 'string', 'max:255'],
            'code'           => ['nullable', 'string', 'max:50', ST::unique(ST::SHIPPING_PARTNERS, 'code')->ignore($partner->id)],
            'phone'          => ['nullable', 'string', 'max:50'],
            'address'        => ['nullable', 'string'],
            'email'          => ['required', 'email', 'unique:users,email,' . ($partner->user_id ?? 0)],
            'password'       => ['nullable', 'string', 'min:6'],
            'internal_notes'   => ['nullable', 'string'],
            'is_active'        => ['nullable', 'boolean'],
            'management_type'  => ['required', Rule::in(ShippingPartnerManagementType::values())],
        ];
    }
}
