<?php

namespace App\Http\Requests;

use App\Models\Order;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOrderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('order_edit');
    }

    public function rules()
    {
        return [
            'order_type' => [
                'required',
            ],
            'client_name' => [
                'string',
                'required',
            ],
            'phone_number' => [
                config('panel.phone_number_validation'), 
                'required',
            ],
            'phone_number_2' => [
                config('panel.phone_number_validation'), 
                'required',
            ],
            'shipping_address' => [
                'required',
            ],
            'shipping_country_name' => [
                'string',
                'required',
            ],
            'shipping_country_cost' => [
                'required',
            ],
            'printing_times' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'date_of_receiving_order' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'excepected_deliverd_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'payment_status' => [
                'required',
            ],
            'delivery_status' => [
                'required',
            ],
            'payment_type' => [
                'required',
            ],
            'discount_code' => [
                'string',
                'nullable',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
            'shipping_country_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
