<?php

namespace App\Http\Requests;

use App\Models\Customer; 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('customer_edit');
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
                'unique:users,email,' . request()->user_id,
            ],
            'phone_number' => [
                'regex:' . config('panel.phone_number_format'), 
                'size:' . config('panel.phone_number_size'), 
                'required',
            ], 
        ];
    }
}
