<?php

namespace App\Http\Requests;

use App\Models\Product; 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'weight' => [
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
        ];
    }
}
