<?php

namespace App\Http\Requests;

use App\Models\Police;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePoliceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('police_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'content' => [
                'required',
            ],
        ];
    }
}
