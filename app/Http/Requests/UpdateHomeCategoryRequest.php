<?php

namespace App\Http\Requests;

use App\Models\HomeCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateHomeCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('home_category_edit');
    }

    public function rules()
    {
        return [
            'category_id' => [
                'required',
                'integer',
            ], 
        ];
    }
}
