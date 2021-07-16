<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'first name' => $this->first_name  ,
            'last name' => $this->last_name,
            'email' => $this->email,
            'age' => $this->age,
            'contact number' => $this->contact_number,
            'symptoms' => $this->symptoms,
            'first symptom date' => $this->first_symptom_date,
            'is_tested' => $this->is_tested,
            'test date' => $this->test_date,
            'is_recovered' => $this->is_recovered,
            'recovery date' => $this->recovery_date,
        ];
    }
}
