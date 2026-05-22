<?php

namespace Modules\Courier\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class MassDestroyCourierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('deliver_man_delete');
    }

    public function rules(): array
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:deliver_men,id',
        ];
    }
}
