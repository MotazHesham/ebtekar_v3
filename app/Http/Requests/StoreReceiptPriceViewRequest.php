<?php

namespace App\Http\Requests;

use App\Models\ReceiptPriceView;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreReceiptPriceViewRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_price_view_create');
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
                'regex:' . config('panel.phone_number_format'), 
                'size:' . config('panel.phone_number_size'), 
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
            'website_setting_id' => [
                'required',
                'integer',
            ], 
        ];
    }
}
