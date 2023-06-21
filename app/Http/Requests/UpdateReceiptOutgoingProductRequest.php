<?php

namespace App\Http\Requests;

use App\Models\ReceiptOutgoingProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReceiptOutgoingProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_outgoing_product_edit');
    }

    public function rules()
    {
        return [
            'description' => [
                'string',
                'nullable',
            ],
            'price' => [
                'required',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
