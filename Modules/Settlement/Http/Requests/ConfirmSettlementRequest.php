<?php

namespace Modules\Settlement\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class ConfirmSettlementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('delivery_settlement_access');
    }

    public function rules(): array
    {
        return [
            'collected_amount' => ['required', 'numeric', 'min:0'],
            'notes'            => ['nullable', 'string', 'max:2000'],
        ];
    }
}
