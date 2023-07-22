<?php

namespace App\Http\Requests;

use App\Models\Designer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDesignerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('designer_edit');
    }

    public function rules()
    {
        return [ 
            'store_name' => [
                'string',
                'required',
                'unique:designers,store_name,' . request()->route('designer')->id,
            ],
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:users,email,' . request()->user_id,
            ],
            'phone_number' => [
                config('panel.phone_number_validation'), 
                'required',
            ],
        ];
    }
}
