<?php

namespace App\Http\Requests;

use App\Models\ReceiptPriceView;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReceiptPriceViewRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_price_view_edit');
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
                config('panel.phone_number_validation'), 
                config('panel.phone_number_language'),  
                'required',
            ],
            'place' => [
                'string',
                'nullable',
            ],
            'relate_duration' => [
                'string',
                'nullable',
            ],
            'supply_duration' => [
                'string',
                'nullable',
            ],
            'payment' => [
                'string',
                'nullable',
            ], 
        ];
    }
}
