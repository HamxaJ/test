<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
            'first_name' => 'required' ,
            'last_name' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
            'contact_number' => 'required|regex:/(03)[0-9]{9}/',
            'symptoms' => 'sometimes|required',
            'first_symptom_date' => 'required_if:symptoms,==,true|date',
            'is_tested' => 'required|boolean',
            'test_date' => 'required_if:is_tested,==,true|date',
            'is_recovered' => 'required|boolean',
            'recovery_date' => 'required_if:is_recovered,==,true|date',
        ];
    }
}
