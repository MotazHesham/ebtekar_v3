<?php

namespace App\Http\Requests;

use App\Models\Subtraction;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSubtractionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('subtraction_edit');
    }

    public function rules()
    {
        return [
            'employee_id' => [
                'required',
                'integer',
            ],
            'amount' => [
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
