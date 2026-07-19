<?php

namespace App\Http\Requests\Api\V1\Customer;

use App\Models\CalendarDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CalendarDateStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(array_keys(CalendarDate::TYPE_SELECT))],
            'description' => 'nullable|max:' . config('panel.max_characters_medium'),
            'date' => 'required|date',
            'reminder_days_before' => 'nullable|integer|min:1|max:365',
        ];
    }
}
