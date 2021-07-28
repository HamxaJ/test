<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'required|string' ,
            'last_name' => 'required|string',
            'email' => 'required|email',
            'age' => 'required|numeric',
            'role' => 'required|string',
            'contact_number' => 'required|regex:/(03)[0-9]{9}/',
            'symptoms' => 'required_if:role,Patient|array',
            'first_symptom_date' => 'required_if:symptoms,==,true|date',
            'is_tested' => 'required_if:role,Patient|boolean',
            'test_date' => 'required_if:is_tested,==,true|date',
            'is_recovered' => 'required_if:role,Patient|boolean',
            'recovery_date' => 'required_if:is_recovered,==,true|date',
            'status' => 'required_if:role,doctor|string',
        ];
    }
}
