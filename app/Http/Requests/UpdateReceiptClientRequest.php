<?php

namespace App\Http\Requests;

use App\Models\ReceiptClient;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReceiptClientRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_client_edit');
    }

    public function rules()
    {
        return [
            'date_of_receiving_order' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'r_branch_id' => [
                'required',
                'integer',
            ], 
            'phone_number' => [
                config('panel.phone_number_validation'), 
                config('panel.phone_number_language'),  
                'required',
            ], 
        ];
    }
}
