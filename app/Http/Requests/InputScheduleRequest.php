<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class InputScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'schedules.*.day' => 'Day',
            'schedules.*.start' => 'start time',
            'schedules.*.end' => 'end time',
        ];
    }

    /**
     * Get the custom error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'schedules.*.start.after_or_equal' => 'Minimum :attribute at 08:00.',
            'schedules.*.start.date_format' => 'The :attribute field must match the format hh:mm.',
            'schedules.*.end.after' => 'The :attribute must be a time after the start time.',
            'schedules.*.end.date_format' => 'The :attribute field must match the format hh:mm.',
            'schedules.*.end.required_unless' => 'The :attribute field is required.',
            'schedules.*.end.before_or_equal' => 'Maximum :attribute at 19:00.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'schedules' => 'required|array',
            'schedules.*.day' => 'required|string',
            'schedules.*.start' => 'nullable|date_format:H:i:s|after_or_equal:08:00:00',
            'schedules.*.end' => 'nullable|required_unless:schedules.*.start,null|date_format:H:i:s|after:schedules.*.start|before_or_equal:19:00:00',
        ];
    }
}
