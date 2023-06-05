<?php

namespace App\Http\Requests;

use App\Models\QualityResponsible;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreQualityResponsibleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('quality_responsible_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'photo' => [
                'required',
            ],
            'phone_number' => [
                'string',
                'required',
            ],
            'wts_phone' => [
                'string',
                'required',
            ],
            'country_code' => [
                'string',
                'required',
            ],
        ];
    }
}
