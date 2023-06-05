<?php

namespace App\Http\Requests;

use App\Models\GeneralSetting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateGeneralSettingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('general_setting_edit');
    }

    public function rules()
    {
        return [
            'logo' => [
                'required',
            ],
            'site_name' => [
                'string',
                'required',
            ],
            'phone_number' => [
                'string',
                'nullable',
            ],
            'facebook' => [
                'string',
                'nullable',
            ],
            'instagram' => [
                'string',
                'nullable',
            ],
            'twitter' => [
                'string',
                'nullable',
            ],
            'telegram' => [
                'string',
                'nullable',
            ],
            'linkedin' => [
                'string',
                'nullable',
            ],
            'whatsapp' => [
                'string',
                'nullable',
            ],
            'youtube' => [
                'string',
                'nullable',
            ],
            'google_plus' => [
                'string',
                'nullable',
            ],
            'photos' => [
                'array',
            ],
            'video_instructions' => [
                'string',
                'nullable',
            ],
        ];
    }
}
