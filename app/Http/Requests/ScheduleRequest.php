<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'weekdays' => 'required|array',
            'weekdays.*.start_time' => 'required|date_format:G:i',
            'weekdays.*.end_time' => 'required|date_format:G:i',
        ];
    }
}
