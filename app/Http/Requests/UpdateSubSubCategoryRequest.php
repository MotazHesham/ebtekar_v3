<?php

namespace App\Http\Requests;

use App\Models\SubSubCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSubSubCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sub_sub_category_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ], 
            'slug' => [
                'string',
                'required',
                'unique:sub_sub_categories,slug,' . request()->id,
            ],
            'meta_title' => [
                'string',
                'nullable',
            ],
            'meta_description' => [
                'string',
                'nullable',
            ],
            'sub_category_id' => [
                'required',
                'integer',
            ], 
        ];
    }
}
