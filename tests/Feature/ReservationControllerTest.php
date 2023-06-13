<?php

namespace Tests\Feature;

use App\Models\Allocation;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomPrices;
use App\Models\User;
use App\Notifications\NewUserReservation;
use Database\Factories\AllocationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;

use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 
     * @test
     * 
     */

    public function itCreatesReservation()
    {

        //just checking the reservation is created successfully
        $user = User::factory()->create();

        $room = Room::factory()->create([

            'capacity' => 3,

        ]);

        $roomPricesData = RoomPrices::factory()->create(['room_id' => $room->id]);

        // dd($roomPricesData);

        $this->actingAs($user);

        $response = $this->postJson('/api/auth/reservations', [
            'room_id' => $room->id,
            'start_date' => '13-08-2023',
            'end_date' => '10-06-2024',
            'reservation_type' => 3
        ]);

        //  dd($response->json());
        $count = $room->capacity - 1;
        $response->assertCreated()
            ->assertJsonPath('data.price', $room->prices->price_for_three_person_booking)
            ->assertJsonPath('data.room.available_slots', $count);
    }



    /**
     * 
     * @test
     * 
     */

    public function itDoesNotMakeReservationOnNonExistingRoom()
    {

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson('/api/auth/reservations', [
            'room_id' => 100000,
            'start_date' => '15-06-2023',
            'end_date' => '10-06-2024',
            'reservation_type' => 3
        ]);

        $response->assertUnprocessable();
    }
    /**
     * 
     * @test
     * 
     */

    public function itDoesNotCreateReservationOnRoomWhichAvailableSlotsAreZero()
    {
        //just checking the reservation is created successfully
        $user = User::factory()->create();
        $room = Room::factory()->create([
            'capacity' => 3,
            'available_slots' => 0,
        ]);
        $this->actingAs($user);
        $roomPricesData = RoomPrices::factory()->create(['room_id' => $room->id]);
        $response = $this->postJson('/api/auth/reservations', [
            'room_id' => $room->id,
            'start_date' => '15-06-2023',
            'end_date' => '10-06-2024',
            'reservation_type' => 3
        ]);

        $response->assertUnprocessable();
    }

    /**
     * 
     * @test
     * 
     */

    public function itDoesNotCreateReservationOnRoomWhichIsNotAvailableForBooking()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create([
            'capacity' => 3,
            'room_status' => 2,
        ]);
        $this->actingAs($user);
        $roomPricesData = RoomPrices::factory()->create(['room_id' => $room->id]);
        $response = $this->postJson('/api/auth/reservations', [
            'room_id' => $room->id,
            'start_date' => '15-06-2023',
            'end_date' => '10-06-2024',
            'reservation_type' => 3
        ]);

        //dd($response->json());

        $response->assertUnprocessable();
    }

    /**
     * 
     * @test
     * 
     */

    public function itFillsAllSlotsIfReservationTypeIsOne()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create([
            'capacity' => 1,
            'available_slots' => 1
        ]);


        $this->actingAs($user);
        $roomPricesData = RoomPrices::factory()->create(['room_id' => $room->id]);
        $response = $this->postJson('/api/auth/reservations', [
            'room_id' => $room->id,
            'start_date' => '15-06-2023',
            'end_date' => '10-06-2024',
            'reservation_type' => 1
        ]);



        $response->assertCreated()
            ->assertJsonPath('data.price', $room->prices->price_for_one_person_booking)
            ->assertJsonPath('data.room.available_slots', 0);
    }



    /**
     * 
     * @test
     * 
     */

    public function itFillsFirstSlotOfRoomIfReservationTypeIs3AndItsFirstReservationOnRoom()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create([
            'capacity' => 3,
            'available_slots' => 3
        ]);


        $this->actingAs($user);
        $roomPricesData = RoomPrices::factory()->create(['room_id' => $room->id]);
        $response = $this->postJson('/api/auth/reservations', [
            'room_id' => $room->id,
            'start_date' => '15-06-2023',
            'end_date' => '10-06-2024',
            'reservation_type' => 3
        ]);

        $slot1 = $room->allocations()->first()->slot1;
        $slot2 = $room->allocations()->first()->slot2;
        $slot3 = $room->allocations()->first()->slot3;



        $response->assertCreated()
            ->assertJsonPath('data.price', $room->prices->price_for_three_person_booking)
            ->assertJsonPath('data.room.available_slots', 2)
            ->assertSee($slot1, 1)
            ->assertSee($slot2, 0)
            ->assertSee($slot3, 0);
    }



    /**
     * 
     * @test
     * 
     */

    public function itFillSecondSlotOfRoomIfReservationTypeIsThreeAndItsSecondReservationOnRoom()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create([
            'capacity' => 3,
            'available_slots' => 3
        ]);
        $roomPricesData = RoomPrices::factory()->create(['room_id' => $room->id]);

        // dd($roomPricesData);
        $this->actingAs($user);
        $reservation = Reservation::factory()->count(1)->create([
            'room_id'      => $room->id,
            'user_id'      => $user->id,
            'price'        => $roomPricesData->first()->price_for_three_person_booking,
        ]);

        $allocations = Allocation::factory()->count(1)->create([
            'room_id' => $room->id,
            'slot1' => 1,
            'slot2' => 0,
            'slot3' => 0,
        ]);

        $room->fill([
            'available_slots' => 2,
        ]);
        $room->save();

        // dd($room);

        // dd($room->allocations()->first());




        $response = $this->postJson('/api/auth/reservations', [
            'room_id' => $room->id,
            'start_date' => '15-06-2023',
            'end_date' => '10-06-2024',
            'reservation_type' => 3
        ]);

        $slot1 = $room->allocations()->first()->slot1;
        $slot2 = $room->allocations()->first()->slot2;
        $slot3 = $room->allocations()->first()->slot3;


        //  dd($response->json());
        $response->assertCreated()
            ->assertJsonPath('data.price', $room->prices->price_for_three_person_booking)
            ->assertJsonPath('data.room.available_slots', 1)
            ->assertSee($slot1, 1)
            ->assertSee($slot2, 1)
            ->assertSee($slot3, 0);
    }


    /**
     * 
     * @test
     * 
     */

    public function itSendsNotificationOnNewReseravtion()
    {
        Notification::fake();
        $user = User::factory()->create();
        $room = Room::factory()->create([
            'capacity' => 1,
            'available_slots' => 1
        ]);


        $this->actingAs($user);
        $roomPricesData = RoomPrices::factory()->create(['room_id' => $room->id]);
        $response = $this->postJson('/api/auth/reservations', [
            'room_id' => $room->id,
            'start_date' => '15-06-2023',
            'end_date' => '10-06-2024',
            'reservation_type' => 1
        ]);


        Notification::assertSentTo($user, NewUserReservation::class);
        $response->assertCreated()
            ->assertJsonPath('data.price', $room->prices->price_for_one_person_booking)
            ->assertJsonPath('data.room.available_slots', 0);
    }


    /**
     * 
     * @test
     * 
     */

    public function itCancelsReservation()
    {

        $user = User::factory()->create();
        $room = Room::factory()->create([
            'capacity' => 1,
            'available_slots' => 1
        ]);
        $roomPricesData = RoomPrices::factory()->create(['room_id' => $room->id]);

        // dd($roomPricesData);
        $this->actingAs($user);
        $reservation = Reservation::factory()->create([
            'room_id'      => $room->id,
            'user_id'      => $user->id,
            'price'        => $roomPricesData->first()->price_for_one_person_booking,
        ]);

        $allocations = Allocation::factory()->count(1)->create([
            'room_id' => $room->id,
            'slot1' => 1,
            'slot2' => 1,
            'slot3' => 1,
            'allocation_type' => Allocation::SEPRATE_ROOM,
        ]);

        $room->fill([
            'available_slots' => 0,
        ]);
        $room->save();

        // dd($reservation->room->allocations->get());

        $response = $this->deleteJson('/api/auth/reservations/' . $reservation->id);
        //dd($response->json());

        $response->assertStatus(200)
            ->assertJsonPath('data.room.available_slots', $room->capacity);
    }



    /**
     * 
     * @test
     * 
     */

    public function itMarksTheThirdSlotToTrueIfReservationBeingCancelledIsType3AndAvailableSlotAreZero()
    {

        $user = User::factory()->create();
        $room = Room::factory()->create([
            'capacity' => 3,
            'available_slots' => 3
        ]);
        $roomPricesData = RoomPrices::factory()->create(['room_id' => $room->id]);

        // dd($roomPricesData);
        $this->actingAs($user);
        $reservation1 = Reservation::factory()->create([
            'room_id'      => $room->id,
            'user_id'      => $user->id,
            'price'        => $roomPricesData->first()->price_for_three_person_booking,
        ]);

        $allocations1 = Allocation::factory()->create([
            'room_id' => $room->id,
            'slot1' => 1,
            'slot2' => 0,
            'slot3' => 0,
            'allocation_type' => Allocation::THREE_PERSONS_ROOM,
        ]);

        $room->fill([
            'available_slots' => 2,
        ]);
        $room->save();


        $reservation2 = Reservation::factory()->create([
            'room_id'      => $room->id,
            'user_id'      => $user->id,
            'price'        => $roomPricesData->first()->price_for_three_person_booking,
        ]);

        $allocations2 = Allocation::factory()->create([
            'room_id' => $room->id,
            'slot1' => 1,
            'slot2' => 1,
            'slot3' => 0,
            'allocation_type' => Allocation::THREE_PERSONS_ROOM,
        ]);

        $room->fill([
            'available_slots' => 1,
        ]);
        $room->save();



        $reservation3 = Reservation::factory()->create([
            'room_id'      => $room->id,
            'user_id'      => $user->id,
            'price'        => $roomPricesData->first()->price_for_three_person_booking,
        ]);

        $allocations3 = Allocation::factory()->create([
            'room_id' => $room->id,
            'slot1' => 1,
            'slot2' => 1,
            'slot3' => 1,
            'allocation_type' => Allocation::THREE_PERSONS_ROOM,
        ]);

        $room->fill([
            'available_slots' => 0,
        ]);
        $room->save();

        // dd($reservation->room->allocations->get());
        $allocationCheck = $room->allocations;

        //  dd($allocationCheck->slot3);
        $response = $this->deleteJson('/api/auth/reservations/' . $reservation3->id);

        //  dd($response->json());

        $response->assertStatus(200)
            ->assertJsonPath('data.room.available_slots', 1);
        $this->assertEquals(false, $allocationCheck->slot3);
    }
}
