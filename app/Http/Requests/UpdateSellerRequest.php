<?php

namespace App\Http\Requests;

use App\Models\Seller; 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UpdateSellerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('seller_edit');
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
                config('panel.phone_number_validation'), 
                config('panel.phone_number_language'),  
                'required',
            ],
            'seller_type' => [
                'required',
            ],
            'discount_code' => [
                'string',
                'nullable',
            ],
            'order_out_website' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'qualification' => [
                'string',
                'nullable',
            ],
            'social_name' => [
                'string',
                'required',
            ],
            'social_link' => [
                'string',
                'required',
            ],
        ];
    }
}
