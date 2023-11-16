<?php

namespace App\Http\Requests;

use App\Models\ReceiptBranch;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReceiptBranchRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('receipt_branch_edit');
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
        ];
    }
}
