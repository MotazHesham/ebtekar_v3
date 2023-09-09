<?php

namespace App\Http\Requests;

use App\Models\ReceiptSocial; 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UpdateReceiptSocialRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_social_edit');
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
            'shipping_address' => [
                'required',
            ],
            'date_of_receiving_order' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'deliver_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'shipping_country_id' => [
                'required',
                'integer',
            ],
            'socials.*' => [
                'integer',
            ],
            'socials' => [
                'array',
            ], 
        ];
    }
}
