<?php

namespace App\Http\Requests;

use App\Models\Currency;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCurrencyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('currency_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'symbol' => [
                'string',
                'required',
            ],
            'exchange_rate' => [
                'required',
            ],
            'code' => [
                'string',
                'required',
            ],
            'half_kg' => [
                'required',
            ],
            'one_kg' => [
                'required',
            ],
            'one_half_kg' => [
                'required',
            ],
            'two_kg' => [
                'required',
            ],
            'two_half_kg' => [
                'required',
            ],
            'three_kg' => [
                'required',
            ],
        ];
    }
}