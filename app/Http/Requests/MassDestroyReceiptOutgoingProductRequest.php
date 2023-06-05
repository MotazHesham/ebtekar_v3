<?php

namespace App\Http\Requests;

use App\Models\ReceiptOutgoingProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyReceiptOutgoingProductRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('receipt_outgoing_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:receipt_outgoing_products,id',
        ];
    }
}
