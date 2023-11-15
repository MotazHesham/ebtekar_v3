<?php

namespace App\Http\Requests;

use App\Models\RBranch;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyRBranchRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('r_branch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:r_branches,id',
        ];
    }
}
