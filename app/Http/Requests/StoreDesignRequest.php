<?php

namespace App\Http\Requests;

use App\Models\Design;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDesignRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('design_create');
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
