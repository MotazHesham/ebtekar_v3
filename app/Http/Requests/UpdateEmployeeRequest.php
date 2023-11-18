<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('employee_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
            ],
            'phone_number' => [
                'string',
                'required',
            ],
            'salery' => [
                'required',
            ],
            'address' => [
                'string',
                'nullable',
            ],
            'job_description' => [
                'string',
                'nullable',
            ],
        ];
    }
}
