<?php

namespace App\Http\Requests;

use App\Models\ReceiptSocialProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreReceiptSocialProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_social_product_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'photos' => [
                'array',
            ],
        ];
    }
}
