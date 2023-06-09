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
                'regex:' . config('panel.phone_number_format'), 
                'size:' . config('panel.phone_number_size'), 
                'required',
            ],
            'wts_phone' => [
                'regex:' . config('panel.phone_number_format'), 
                'size:' . config('panel.phone_number_size'), 
                'required',
            ],
            'country_code' => [
                'string',
                'required',
            ],
        ];
    }
}
