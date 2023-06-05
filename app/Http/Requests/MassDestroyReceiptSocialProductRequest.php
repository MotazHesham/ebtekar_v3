<?php

namespace App\Http\Requests;

use App\Models\ReceiptSocialProduct;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyReceiptSocialProductRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('receipt_social_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:receipt_social_products,id',
        ];
    }
}
