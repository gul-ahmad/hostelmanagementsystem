<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'hostel_id' => Branch::factory(),
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->words(6, true),
            'address_line1' => $this->faker->words(5, true),
            'address_line2' => $this->faker->words(5, true),
            'status' => 1,
            'hidden' => false,
            'total_rooms' => $this->faker->numberBetween(50, 100),
            'floors' => 2
        ];
    }
}
