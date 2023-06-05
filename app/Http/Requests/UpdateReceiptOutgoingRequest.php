<?php

namespace App\Http\Requests;

use App\Models\ReceiptOutgoing;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReceiptOutgoingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_outgoing_edit');
    }

    public function rules()
    {
        return [
            'date_of_receiving_order' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'client_name' => [
                'string',
                'required',
            ],
            'phone_number' => [
                'string',
                'required',
            ],
            'total_cost' => [
                'required',
            ],
            'staff_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
