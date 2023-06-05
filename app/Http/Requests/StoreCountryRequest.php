<?php

namespace App\Http\Requests;

use App\Models\Country;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCountryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('country_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'cost' => [
                'required',
            ],
            'code' => [
                'string',
                'nullable',
            ],
            'type' => [
                'required',
            ],
            'status' => [
                'required',
            ],
            'website' => [
                'required',
            ],
        ];
    }
}
