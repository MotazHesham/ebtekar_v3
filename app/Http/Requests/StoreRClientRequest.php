<?php

namespace App\Http\Requests;

use App\Models\RClient;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreRClientRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('r_client_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'phone_number' => [
                config('panel.phone_number_validation'), 
                config('panel.phone_number_language'),  
                'required',
            ],
        ];
    }
}
