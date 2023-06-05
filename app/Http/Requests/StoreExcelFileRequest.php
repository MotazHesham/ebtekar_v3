<?php

namespace App\Http\Requests;

use App\Models\ExcelFile;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreExcelFileRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('excel_file_create');
    }

    public function rules()
    {
        return [
            'uploaded_file' => [
                'required',
            ],
            'result_file' => [
                'required',
            ],
            'results' => [
                'required',
            ],
        ];
    }
}
