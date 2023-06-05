<?php

namespace App\Http\Requests;

use App\Models\CommissionRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCommissionRequestRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('commission_request_edit');
    }

    public function rules()
    {
        return [
            'status' => [
                'required',
            ],
            'total_commission' => [
                'required',
            ],
            'payment_method' => [
                'required',
            ],
            'transfer_number' => [
                'string',
                'nullable',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
            'created_by_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
