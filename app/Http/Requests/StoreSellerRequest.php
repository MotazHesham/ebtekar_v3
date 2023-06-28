<?php

namespace App\Http\Requests;

use App\Models\Seller; 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class StoreSellerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('seller_create');
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
            'order_in_website' => [
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
