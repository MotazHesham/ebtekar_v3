<?php

namespace App\Http\Requests;

use App\Models\ReceiptClientProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyReceiptClientProductRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('receipt_client_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:receipt_client_products,id',
        ];
    }
}
