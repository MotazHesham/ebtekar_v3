<?php

namespace App\Http\Requests;

use App\Models\Customer; 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class StoreCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('customer_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:users',
            ],
            'phone_number' => [
                'regex:' . config('panel.phone_number_format'), 
                'size:' . config('panel.phone_number_size'), 
                'required',
            ],
            'password' => [
                'required',
            ],
            'website_setting_id' => [
                'required',
                'integer',
            ], 
        ];
    }
}
