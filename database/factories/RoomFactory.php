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
        // $roomNumber = $this->faker->unique(true)->numberBetween(100, 999);

       

        // // Check if the generated room number already exists in the database
        // $existingRoom = Room::where('room_number', $roomNumber)->first();



        // if ($existingRoom) {
        //     // If the room number exists, generate a new one
        //     return $this->definition();
        // }

        return [
            'branch_id' => Branch::factory(),
            'room_number' =>  $this->faker->unique(true)->numberBetween(100, 999),
            'room_floor_number' => $this->faker->numberBetween(1, 2),
            'room_status' => Room::AVAILABLE_FOR_BOOKING,
            'hidden' => false,
            'capacity' => 3,
        ];
    }
}
