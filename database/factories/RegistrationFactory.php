<?php

namespace Database\Factories;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        return [
            'member_type' => 'new',
            'nok_id' => null,
            'doj' => now()->toDateString(),
            'memberName' => $this->faker->name(),
            'age' => $this->faker->numberBetween(20, 60),
            'gender' => $this->faker->randomElement(['M','F']),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => $this->faker->numerify('5########'),
            'department' => 'Dept',
            'job_title' => 'Nurse',
            'institution' => 'Hospital',
            'passport' => $this->faker->bothify('P########'),
            'civil_id' => $this->faker->bothify('CIV#####'),
            'address' => $this->faker->address(),
            'renewal_status' => 'approved',
            'card_valid_until' => now()->endOfYear()->toDateString(),
        ];
    }
}


