<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function itListsAllTheRooms()
    {
        Room::factory()->count(3)->create();
        $response = $this->get('/api/rooms');

        $response->assertOk();
        $this->assertNotNull($response->json('data')[0]['id']);
        //below both are same but with different ways to count the data
        $this->assertCount(3, $response->json('data'));
        $response->assertJsonCount(3, 'data');
    }

    /**
     * @test
     */
    public function itListsAllOfficesInPaginatedWay()
    {
        Room::factory()->count(3)->create();
        $response = $this->get('/api/rooms');
        $response->assertOk();
        $this->assertNotNull($response->json('data')[0]['id']);
        //below both are same but with different ways to count the data
        $this->assertCount(3, $response->json('data'));
        $response->assertJsonCount(3, 'data');
        $this->assertNotNull($response->json('meta'));
    }

    /**
     * @test
     */
    public function itDisplayOnlyRoomsThatAreNotHiddenAndAvailableForBooking()
    {
        Room::factory()->count(3)->create();
        Room::factory(['hidden' => true])->create();
        Room::factory(['room_status' => Room::NOT_AVAILABLE_FOR_BOOKING])->create();

        $response = $this->get('/api/rooms');
        $response->assertOk();
        $response->assertJsonCount(3, 'data');
        $this->assertNotNull($response->json('meta'));
    }

    /**
     * @test
     */
    public function itFiltersRoomsByRoomStatus()
    {
        // Room::factory()->count(3)->create();
        $room = Room::factory(['room_status' => Room::NOT_AVAILABLE_FOR_BOOKING])->create();
        Room::factory(['room_status' => Room::AVAILABLE_FOR_BOOKING])->create(); // Add this line

        $response = $this->get('/api/rooms?room_status=' . 2);
        //  $response->dump();
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $this->assertEquals($room->id, $response->json('data')[0]['id']);
    }
}
