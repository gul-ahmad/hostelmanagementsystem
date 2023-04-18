<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status' => 1,
            'start_date' => now()->addDay(1)->format('Y-m-d'),
            'end_date' => now()->addDay(30)->format('Y-m-d'),
            'wifi_password' => '',
            'room_id' => Room::factory(),
            'student_id' => Student::factory(),
        ];
    }
}
