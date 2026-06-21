<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Models\Contactu;
use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
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
            'name' => 'required|max:'.config('panel.max_characters_short'),
            'phone' => 'required|max:'.config('panel.max_characters_short'),
            'message' => 'required|max:'.config('panel.max_characters_long'),
            'application' => 'required|in:' . implode(',', array_keys(Contactu::APPLICATION_SELECT)),
        ];
    }
}
