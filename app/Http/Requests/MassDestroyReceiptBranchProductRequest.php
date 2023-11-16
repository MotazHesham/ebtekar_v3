<?php

namespace App\Http\Requests;

use App\Models\ReceiptBranchProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyReceiptBranchProductRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('receipt_branch_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:receipt_branch_products,id',
        ];
    }
}
