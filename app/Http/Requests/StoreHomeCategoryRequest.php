<?php

namespace App\Http\Requests;

use App\Models\HomeCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreHomeCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('home_category_create');
    }

    public function rules()
    {
        return [
            'category_id' => [
                'required',
                'integer',
            ],
            'website_setting_id' => [
                'required',
                'integer',
            ], 
        ];
    }
}
