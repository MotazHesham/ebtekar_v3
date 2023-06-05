<?php

namespace App\Http\Requests;

use App\Models\Designe;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDesigneRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('designe_edit');
    }

    public function rules()
    {
        return [
            'design_name' => [
                'string',
                'required',
            ],
            'profit' => [
                'required',
            ],
            'status' => [
                'required',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
            'mockup_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
