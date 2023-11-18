<?php

namespace App\Http\Requests;

use App\Models\EmployeeFinancial;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEmployeeFinancialRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('employee_financial_create');
    }

    public function rules()
    {
        return [
            'employee_id' => [
                'required',
                'integer',
            ],
            'financial_category_id' => [
                'required',
                'integer',
            ],
            'amount' => [
                'required',
            ],
        ];
    }
}
