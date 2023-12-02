<?php

namespace App\Http\Requests;

use App\Models\EmployeeFinancial;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEmployeeFinancialRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('employee_financial_edit');
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
            'entry_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
        ];
    }
}
