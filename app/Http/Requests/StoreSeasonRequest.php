<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreSeasonRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('season_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'max:255',
            ],
            'year' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
            ],
            'start_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'end_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            try {
                $start = Carbon::createFromFormat(config('panel.date_format'), $this->input('start_date'))->startOfDay();
                $end = Carbon::createFromFormat(config('panel.date_format'), $this->input('end_date'))->startOfDay();
            } catch (\Exception $e) {
                return;
            }

            if ($end->lt($start)) {
                $validator->errors()->add('end_date', __('validation.after_or_equal', ['attribute' => __('cruds.season.fields.end_date'), 'date' => __('cruds.season.fields.start_date')]));
            }
        });
    }
}
