<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    protected $model = Member::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_type' => 'regular',
            'nok_id' => 'NOK' . str_pad((string) fake()->unique()->numberBetween(1000, 999999), 6, '0', STR_PAD_LEFT),
            'doj' => fake()->date(),
            'memberName' => fake()->name(),
            'age' => fake()->numberBetween(25, 65),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password', // Will be hashed by Member model mutator
            'mobile' => '+965' . fake()->numerify('########'),
            'whatsapp' => '+965' . fake()->numerify('########'),
            'department' => fake()->randomElement(['ICU', 'ER', 'Surgery', 'Pediatrics', 'General']),
            'job_title' => fake()->randomElement(['Staff Nurse', 'Senior Nurse', 'Charge Nurse', 'Head Nurse']),
            'institution' => fake()->company() . ' Hospital',
            'passport' => fake()->bothify('??######'),
            'civil_id' => fake()->numerify('############'),
            'blood_group' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
            'address' => fake()->address(),
            'phone_india' => '+91' . fake()->numerify('##########'),
            'nominee_name' => fake()->name(),
            'nominee_relation' => fake()->randomElement(['Spouse', 'Parent', 'Sibling', 'Child']),
            'nominee_contact' => fake()->phoneNumber(),
            'guardian_name' => fake()->name(),
            'guardian_contact' => fake()->phoneNumber(),
            'bank_account_name' => fake()->name(),
            'account_number' => fake()->numerify('##############'),
            'ifsc_code' => fake()->bothify('????#######'),
            'bank_branch' => fake()->city(),
            'login_status' => 'approved',
            'renewal_status' => null,
            'card_issued_at' => now(),
            'last_renewed_at' => null,
            'renewal_count' => 0,
            'card_valid_until' => now()->addYear(),
            'renewal_date' => null,
            'photo_path' => null,
            'qr_code_path' => null,
            'renewal_payment_proof' => null,
            'renewal_requested_at' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the member is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'login_status' => 'pending',
        ]);
    }

    /**
     * Indicate that the member is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'login_status' => 'rejected',
        ]);
    }

    /**
     * Indicate that the member has renewed.
     */
    public function renewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'renewal_status' => 'approved',
            'last_renewed_at' => now(),
            'renewal_count' => fake()->numberBetween(1, 5),
        ]);
    }
}

