<?php

namespace App\Http\Requests;

use App\Models\ReceiptClient;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreReceiptClientRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_client_create');
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
            'website_setting_id' => [
                'required',
                'integer',
            ], 
        ];
    }
}
