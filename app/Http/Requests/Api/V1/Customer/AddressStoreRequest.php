<?php

namespace App\Http\Requests\Api\V1\Customer;

use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:' . config('panel.max_characters_short'),
            'client_name' => 'required|max:' . config('panel.max_characters_short'),
            'country_id' => 'required|exists:countries,id',
            'address' => 'required|max:' . config('panel.max_characters_medium'),
            'phone' => 'required|max:' . config('panel.max_characters_short'),
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_default' => 'required|boolean',
        ];
    }
}
