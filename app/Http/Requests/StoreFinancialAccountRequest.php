<?php

namespace App\Http\Requests;

use App\Models\FinancialAccount;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFinancialAccountRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('financial_account_create');
    }

    public function rules()
    {
        return [
            'account' => [
                'string',
                'required',
            ],
            'description' => [
                'string',
                'nullable',
            ],
        ];
    }
}
