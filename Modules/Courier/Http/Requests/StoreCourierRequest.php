<?php

namespace Modules\Courier\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('deliver_man_create');
    }

    public function rules(): array
    {
        return [
            'name'                => ['string', 'required'],
            'email'               => ['required', 'unique:users'],
            'phone_number'        => [
                config('panel.phone_number_validation'),
                config('panel.phone_number_language'),
                'required',
            ],
            'password'            => ['required'],
            'shipping_partner_id' => ['nullable', 'exists:shipping_partners,id'],
            'status'              => ['nullable', 'in:active,inactive'],
            'internal_notes'      => ['nullable', 'string'],
            'photo'               => ['nullable', 'image'],
        ];
    }
}
