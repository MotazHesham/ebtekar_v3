<?php

namespace Modules\Shipping\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreShippingPartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('shipping_partner_create');
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'code'           => ['nullable', 'string', 'max:50', 'unique:shipping_partners,code'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'address'        => ['nullable', 'string'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'password'       => ['required', 'string', 'min:6'],
            'internal_notes' => ['nullable', 'string'],
            'is_active'      => ['nullable', 'boolean'],
        ];
    }
}
