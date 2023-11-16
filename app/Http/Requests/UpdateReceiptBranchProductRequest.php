<?php

namespace App\Http\Requests;

use App\Models\ReceiptBranchProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReceiptBranchProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_branch_product_edit');
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
