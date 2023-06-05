<?php

namespace App\Http\Requests;

use App\Models\Designer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDesignerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('designer_edit');
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
                'unique:designers,store_name,' . request()->route('designer')->id,
            ],
        ];
    }
}
