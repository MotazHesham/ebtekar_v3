<?php

namespace App\Http\Requests;

use App\Models\DeliverMan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDeliverManRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('deliver_man_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:deliver_men,id',
        ];
    }
}
