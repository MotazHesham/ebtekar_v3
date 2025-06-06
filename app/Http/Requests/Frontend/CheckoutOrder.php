<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
            ],
            'last_name' => [
                'required',
            ],
            'email' => [
                'nullable',
                'email',
                'unique:users'
            ], 
            'phone_number' => [
                config('panel.phone_number_validation'), 
                'required',
            ],
            'phone_number_2' => [
                config('panel.phone_number_validation'), 
                'nullable',
            ],
            'wallet_phone' => [
                config('panel.phone_number_validation'), 
                'nullable',
            ],
            'shipping_address' => [
                'required',
            ],
            'payment_option' => [
                'required',
                'in:cash_on_delivery,paymob,wallet',
            ], 
            'date_of_receiving_order' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'excepected_deliverd_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ], 
            'country_id' => [
                'required',
                'integer',
            ], 
        ];
    }
}
