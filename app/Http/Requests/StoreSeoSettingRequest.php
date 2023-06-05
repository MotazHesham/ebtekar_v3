<?php

namespace App\Http\Requests;

use App\Models\SeoSetting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSeoSettingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('seo_setting_create');
    }

    public function rules()
    {
        return [
            'keyword' => [
                'required',
            ],
            'author' => [
                'string',
                'required',
            ],
            'sitremap_link' => [
                'string',
                'required',
            ],
            'description' => [
                'required',
            ],
        ];
    }
}
