<?php

namespace App\Http\Requests;

use App\Models\Subscribe;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSubscribeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'string', 
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ];
    }
}
