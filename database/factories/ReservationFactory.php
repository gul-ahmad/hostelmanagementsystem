<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{


    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reservation::class;




    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'status' => Reservation::STATUS_ACTIVE,
            'start_date'      => now()->addDay(1)->format('Y-m-d'),
            'end_date'        => now()->addDay(30)->format('Y-m-d'),
            'wifi_password'   => '',
            'room_id'         => Room::factory(),
            'student_id'      => Student::factory(),
        ];
    }
    public function cancelled(): Factory
    {

        return $this->state([

            'status' => Reservation::STATUS_CANCELLED

        ]);
    }
}
