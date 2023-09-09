<?php

namespace App\Http\Requests;

use App\Models\Designer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDesignerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('designer_create');
    }

    public function rules()
    {
        return [ 
            'store_name' => [
                'string',
                'required',
                'unique:designers',
            ],
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:users',
            ],
            'phone_number' => [
                config('panel.phone_number_validation'), 
                config('panel.phone_number_language'),  
                'required',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
