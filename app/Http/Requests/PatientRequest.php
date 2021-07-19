<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
            'first_name' => 'required|string|max:25' ,
            'last_name' => 'required|string|max:25',
            'email' => 'required|email|unique:patients',
            'password' => 'required|confirmed|min:6',
            'age' => 'required|numeric|digits_between:1,3',
            'contact_number' => 'required|regex:/(03)[0-9]{9}/',
            'symptoms' => 'sometimes|array',
            'first_symptom_date' => 'required_if:symptoms,true|date_format:Y-m-d',
            'is_tested' => 'required|boolean',
            'test_date' => 'required_if:is_tested,true|date_format:Y-m-d',
            'is_recovered' => 'required|boolean',
            'recovery_date' => 'required_if:is_recovered,true|date_format:Y-m-d',
        ];
    }
}
