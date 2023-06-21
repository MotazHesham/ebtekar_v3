<?php

namespace App\Http\Requests;

use App\Models\ReceiptCompany;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReceiptCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_company_edit');
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
                'regex:' . config('panel.phone_number_format'), 
                'size:' . config('panel.phone_number_size'), 
                'required',
            ],
            'phone_number_2' => [
                'regex:' . config('panel.phone_number_format'), 
                'size:' . config('panel.phone_number_size'), 
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
