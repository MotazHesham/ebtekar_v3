<?php

namespace App\Http\Requests;

use App\Models\Police;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPoliceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('police_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:polices,id',
        ];
    }
}
