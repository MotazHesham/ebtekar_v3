<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'unit_price' => [
                'required',
            ],
            'purchase_price' => [
                'required',
            ], 
            'video_provider' => [
                'string',
                'nullable',
            ],
            'video_link' => [
                'string',
                'nullable',
            ],
            'description' => [
                'required',
            ],
            'photos' => [
                'array',
                'required',
            ],
            'photos.*' => [
                'required',
            ],
            'meta_title' => [
                'string',
                'nullable',
            ], 
            'category_id' => [
                'required',
                'integer',
            ],
            'sub_category_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
