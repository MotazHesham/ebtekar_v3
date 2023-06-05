<?php

namespace App\Http\Requests;

use App\Models\BannedPhone;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyBannedPhoneRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('banned_phone_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:banned_phones,id',
        ];
    }
}
