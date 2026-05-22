<?php

namespace Modules\Returns\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UploadReturnAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('delivery_return_access');
    }

    public function rules(): array
    {
        return [
            'attachments'   => ['required', 'array', 'min:1'],
            'attachments.*' => ['file', 'image', 'max:5120'],
        ];
    }
}
