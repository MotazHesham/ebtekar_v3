<?php

namespace App\Http\Requests;

use App\Models\SeoSetting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySeoSettingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('seo_setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:seo_settings,id',
        ];
    }
}
