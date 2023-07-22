<?php

namespace App\Http\Requests;

use App\Models\User; 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_edit');
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
                'unique:users,email,' . request()->route('user')->id,
            ],
            'phone_number' => [
                config('panel.phone_number_validation'), 
                'required',
            ],
            'roles.*' => [
                'integer',
            ],
            'roles' => [
                'array',
            ],
            'website_setting_id' => [
                'required',
            ],
        ];
    }
}
