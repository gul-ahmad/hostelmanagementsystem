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
            'price_for_one_person_booking' => 45000,
            'price_for_two_person_booking' => 22500,
            'price_for_three_person_booking' => 15000,
            'discount_on_full_allocation' => 0,
        ];
    }
}
