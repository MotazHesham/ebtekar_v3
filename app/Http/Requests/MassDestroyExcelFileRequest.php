<?php

namespace App\Http\Requests;

use App\Models\ExcelFile;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyExcelFileRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('excel_file_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:excel_files,id',
        ];
    }
}
