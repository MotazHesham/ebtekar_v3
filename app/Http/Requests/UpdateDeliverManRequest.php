<?php

namespace App\Http\Requests;

use App\Models\DeliverMan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDeliverManRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('deliver_man_edit');
    }

    public function rules()
    {
        return [
            'user' => [
                'string',
                'required',
            ],
        ];
    }
}
