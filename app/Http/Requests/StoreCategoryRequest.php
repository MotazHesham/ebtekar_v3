<?php

namespace App\Http\Requests;

use App\Models\Category;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('category_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'banner' => [
                'required',
            ],
            'icon' => [
                'required',
            ], 
            'meta_title' => [
                'string',
                'nullable',
            ],
            'meta_description' => [
                'string',
                'nullable',
            ],
            'website_setting_id' => [
                'required',
                'integer',
            ], 
        ];
    }
}
