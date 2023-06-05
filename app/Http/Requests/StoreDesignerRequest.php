<?php

namespace App\Http\Requests;

use App\Models\Designer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDesignerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('designer_create');
    }

    public function rules()
    {
        return [
            'user' => [
                'string',
                'required',
            ],
            'store_name' => [
                'string',
                'required',
                'unique:designers',
            ],
        ];
    }
}
