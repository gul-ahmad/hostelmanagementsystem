<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'branch_id'         => Branch::factory(),
            'room_number'       => $this->faker->unique(true)->numberBetween(1, 50),
            'room_floor_number' => $this->faker->unique(true)->numberBetween(1, 2),
            'room_status'       => Room::AVAILABLE_FOR_BOOKING,
            'hidden'            => false,
            'capactiy'          => 3,
        ];
    }
}
