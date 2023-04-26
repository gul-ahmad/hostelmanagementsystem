<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'full_name'                   => $this->faker->words(2, true),
            'email'                       => fake()->unique()->safeEmail(),
            'cnic'                        => $this->faker->numberBetween(5000, 5000000),
            'contact_no'                  => $this->faker->numberBetween(1, 1000000),
            'emergency_contact_number'    => $this->faker->numberBetween(1, 100000000),
            'gender'                      => $this->faker->randomElement(['male', 'female']),
            'address'                     => $this->faker->sentence,
            'university/college'          => $this->faker->words(2, true),
            'nationality'                 => 'Pakistan1',
            'passport_number'             => null,
            'room_id'                     => Room::factory()

        ];
    }
}
