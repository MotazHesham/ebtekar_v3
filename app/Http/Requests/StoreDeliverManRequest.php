<?php

namespace App\Http\Requests;

use App\Models\DeliverMan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDeliverManRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('deliver_man_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:users',
            ],
            'phone_number' => [
                'regex:' . config('panel.phone_number_format'), 
                'size:' . config('panel.phone_number_size'), 
                'required',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
