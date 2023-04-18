<?php

namespace Database\Factories;

use App\Models\Allocation;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Allocation>
 */
class AllocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'room_id' => Room::factory(),
            'slot1' => true,
            'slot2' => true,
            'slot3' => true,
            'allocation_type' => Allocation::THREE_PERSONS_ROOM,
        ];
    }
}
