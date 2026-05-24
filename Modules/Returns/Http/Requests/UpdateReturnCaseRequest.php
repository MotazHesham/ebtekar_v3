<?php

namespace Modules\Returns\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Returns\Enums\ReturnCaseStatus;
use Modules\Returns\Enums\ReturnReason;
use Symfony\Component\HttpFoundation\Response;

class UpdateReturnCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        abort_if(Gate::denies('delivery_return_admin_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['nullable', Rule::in(array_column(ReturnReason::cases(), 'value'))],
            'note'   => ['nullable', 'string', 'max:2000'],
            'status' => ['nullable', Rule::in(array_column(ReturnCaseStatus::cases(), 'value'))],
        ];
    }
}
