<?php

namespace App\Http\Requests;

use App\Models\RClient;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRClientRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('r_client_edit');
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
