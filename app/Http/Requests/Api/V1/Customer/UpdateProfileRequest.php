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
                'max:'.config('panel.max_characters_short'),
            ],
            'email' => [
                'nullable',
                'unique:users,email,' . auth()->user()->id,
                'max:'.config('panel.max_characters_short'),
            ], 
            'phone' => [ 
                'required',
                'unique:users,phone,' . auth()->user()->id,
                config('panel.phone_validation'),
            ],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:' . config('panel.max_file_size'),
            ],
            'city_id' => [
                'nullable',
                'exists:cities,id',
            ],
        ];
    }
}
