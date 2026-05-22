<?php

namespace Modules\Shipping\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('delivery_order_show');
    }

    public function rules(): array
    {
        return [
            'body'      => ['required', 'string'],
            'parent_id' => ['nullable', 'exists:delivery_notes,id'],
        ];
    }
}
