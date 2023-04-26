<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Room::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'branch_id'         => Branch::factory(),
            'room_number'       => $this->faker->unique(true)->numberBetween(100, 999),
            'room_floor_number' => $this->faker->numberBetween(1, 2),
            'room_status'       => Room::AVAILABLE_FOR_BOOKING,
            'hidden'            => false,
            'capactiy'          => 3,
        ];
    }

    
}
