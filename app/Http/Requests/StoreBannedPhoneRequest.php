<?php

namespace App\Http\Requests;

use App\Models\BannedPhone;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreBannedPhoneRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('banned_phone_create');
    }

    public function rules()
    {
        return [
            'phone' => [
                'regex:' . config('panel.phone_number_format'), 
                'size:' . config('panel.phone_number_size'), 
                'required',
            ],
            'reason' => [
                'required',
            ],
        ];
    }
}
