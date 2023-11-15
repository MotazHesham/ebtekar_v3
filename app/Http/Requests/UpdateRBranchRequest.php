<?php

namespace App\Http\Requests;

use App\Models\RBranch;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRBranchRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('r_branch_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'phone_number' => [
                'string',
                'required',
            ],
            'r_client_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
