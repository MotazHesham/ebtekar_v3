<?php

namespace App\Http\Requests\Api\V1\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name' => [
                'string',
                'required',
                'max:' . config('panel.max_characters_short'),
            ],
            'email' => [
                'nullable',
                'unique:users,email,' . auth()->user()->id,
                'max:' . config('panel.max_characters_short'),
            ],
            'phone_number' => [
                'required',
                'unique:users,phone_number,' . auth()->user()->id,
                'regex:' . config('panel.phone_number_format'),
                'size:' . config('panel.phone_number_size'),
            ],
        ];
    }
}
