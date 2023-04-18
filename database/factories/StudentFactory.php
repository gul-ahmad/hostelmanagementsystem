<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'full_name' => $this->faker->words(2, true),
            'email' => fake()->unique()->safeEmail(),
            'cnic' => $this->faker->numberBetween(),
            'contact_number' => $this->faker->numberBetween(),
            'emergency_contact_number' => $this->faker->numberBetween(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->sentence,
            'university/college' => $this->faker->words(2, true),
            'nationality' => 'Pakistan',
            'passport_number' => null


        ];
    }
}
