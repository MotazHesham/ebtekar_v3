<?php

namespace App\Http\Requests;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserAlertRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_alert_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
                'max:255',
            ],
            'alert_text' => [
                'string',
                'required',
            ],
            'alert_link' => [
                'string',
                'nullable',
            ],
            'user_type' => [
                'string',
                'required',
                Rule::in(array_keys(User::USER_TYPE_SELECT)),
            ],
            'recipient_mode' => [
                'string',
                'required',
                Rule::in(['all', 'specific']),
            ],
            'users' => [
                'required_if:recipient_mode,specific',
                'array',
                'min:1',
            ],
            'users.*' => [
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('user_type', $this->input('user_type'));
                }),
            ],
            'send_push_notification' => [
                'boolean',
            ],
        ];
    }
}
