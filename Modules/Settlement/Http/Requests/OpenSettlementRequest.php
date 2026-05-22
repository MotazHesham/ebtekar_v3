<?php

namespace Modules\Settlement\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class OpenSettlementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('delivery_settlement_access');
    }

    public function rules(): array
    {
        return [
            'courier_id'              => ['required', 'integer', 'exists:deliver_men,id'],
            'settlement_date'         => ['nullable', 'date'],
            'include_all_unsettled'   => ['nullable', 'boolean'],
        ];
    }
}
