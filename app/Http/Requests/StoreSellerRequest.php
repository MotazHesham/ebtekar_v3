<?php

namespace App\Http\Requests;

use App\Models\Seller;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSellerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('seller_create');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
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
