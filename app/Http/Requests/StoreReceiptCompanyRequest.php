<?php

namespace App\Http\Requests;

use App\Models\ReceiptCompany;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreReceiptCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_company_create');
    }

    public function rules()
    {
        return [
            'client_name' => [
                'string',
                'required',
            ],
            'client_type' => [
                'required',
            ],
            'phone_number' => [
                config('panel.phone_number_validation'), 
                config('panel.phone_number_language'),  
                'required',
            ],
            'phone_number_2' => [
                config('panel.phone_number_validation'), 
                config('panel.phone_number_language'),  
                'nullable',
            ],
            'deliver_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'date_of_receiving_order' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'shipping_address' => [
                'required',
            ],
            'description' => [
                'required',
            ],
            'photos' => [
                'array',
            ],
            'shipping_country_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
