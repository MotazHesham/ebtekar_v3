<?php

namespace App\Http\Requests;

use App\Models\ReceiptPriceViewProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReceiptPriceViewProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_price_view_product_edit');
    }

    public function rules()
    {
        return [
            'description' => [
                'string',
                'required',
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
            'total_cost' => [
                'required',
            ],
            'receipt_price_view_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
