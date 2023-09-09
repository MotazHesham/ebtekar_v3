<?php

namespace App\Http\Requests;

use App\Models\BannedPhone;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateBannedPhoneRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('banned_phone_edit');
    }

    public function rules()
    {
        return [
            'phone' => [
                config('panel.phone_number_validation'), 
                config('panel.phone_number_language'),  
                'required',
            ],
            'reason' => [
                'required',
            ],
        ];
    }
}
