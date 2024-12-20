<?php

namespace App\Http\Requests;

use App\Models\FinancialCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFinancialCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('financial_category_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'type' => [
                'required',
            ],
        ];
    }
}
