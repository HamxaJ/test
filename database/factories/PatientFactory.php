<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {    
        return [
            'first_name' => $this->faker->firstNameMale() ,
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'age' => $this->faker->randomNumber(2, true),
            'password' => '$2y$10$LRzgmzgxI9pdz.BJh081huEpW7nOx4wwGItF78z057xNDBUr/3EoW',
            // 'contact_number' => $this->faker->e164PhoneNumber(),
            // 'symptoms' => '["Flu", "fever", "dry cough"]',
            'first_symptom_date' => $this->faker->date(),
            'is_tested' => true,
            'test_date' => $this->faker->date(),
            'is_recovered' => true,
            'recovery_date' => $this->faker->date(),
        ];
    }
}
