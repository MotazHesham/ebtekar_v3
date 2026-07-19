<?php

namespace App\Http\Requests\Api\V1\Customer;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
                'required',
                'max:' . config('panel.max_characters_short'),
            ],
            'password' => [
                'required',
                'min:' . config('panel.password_min_length'),
                'max:' . config('panel.password_max_length'),
            ],
            'phone_number' => [
                'required',
                'unique:users',
                'regex:' . config('panel.phone_number_format'),
                'size:' . config('panel.phone_number_size'),
            ],
            'email' => [
                'required',
                'unique:users',
            ],
        ];
    }
}
