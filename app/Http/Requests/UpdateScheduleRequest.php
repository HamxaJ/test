<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
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
            'weekday' => 'required|string',
            'date' => 'required',
            'start_time' => 'required|date_format:G:i',
            'end_time' => 'required|date_format:G:i',
            'status' => 'required',
        ];
    }
}
