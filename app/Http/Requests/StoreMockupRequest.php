<?php

namespace App\Http\Requests;

use App\Models\Mockup;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMockupRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mockup_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'preview_1' => [
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
            'purchase_price' => [
                'required',
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
