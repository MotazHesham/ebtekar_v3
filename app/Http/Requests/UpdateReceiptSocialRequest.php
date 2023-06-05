<?php

namespace App\Http\Requests;

use App\Models\ReceiptSocial;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

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
                'string',
                'required',
            ],
            'phone_number_2' => [
                'string',
                'nullable',
            ],
            'shipping_country_name' => [
                'string',
                'required',
            ],
            'shipping_country_cost' => [
                'required',
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
            'delivery_status' => [
                'required',
            ],
            'payment_status' => [
                'required',
            ],
            'playlist_status' => [
                'required',
            ],
            'staff_id' => [
                'required',
                'integer',
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
