<?php

namespace App\Http\Requests;

use App\Models\ReceiptBranchProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreReceiptBranchProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_branch_product_create');
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
