<?php

namespace App\Http\Requests;

use App\Models\Banner;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreBannerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('banner_create');
    }

    public function rules()
    {
        return [
            'photo' => [
                'required',
            ],
            'url' => [
                'string',
                'nullable',
            ],
            'position' => [
                'required',
            ], 
            'website_setting_id' => [
                'required',
                'integer',
            ], 
        ];
    }
}
