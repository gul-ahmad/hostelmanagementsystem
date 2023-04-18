<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoomPrices>
 */
class RoomPricesFactory extends Factory
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
            'start_date' => now()->addDay(1)->format('Y-m-d'),
            'end_date' => now()->addDay(30)->format('Y-m-d'),
            'price_per_head' => 15000,
            'price_per_room' => 45000,
            'discount_on_full_allocation' => 0,
        ];
    }
}
