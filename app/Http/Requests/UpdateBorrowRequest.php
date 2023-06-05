<?php

namespace App\Http\Requests;

use App\Models\Borrow;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateBorrowRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('borrow_edit');
    }

    public function rules()
    {
        return [
            'employee_id' => [
                'required',
                'integer',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
