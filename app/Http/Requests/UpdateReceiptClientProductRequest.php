<?php

namespace App\Http\Requests;

use App\Models\ReceiptClientProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReceiptClientProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_client_product_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'price' => [
                'required',
            ],
        ];
    }
}
