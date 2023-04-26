<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Reservation;
use App\Models\Room;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): AnonymousResourceCollection
    {


        //  DB::enableQueryLog();
        $rooms = Room::query()
            ->when(request('room_status'), function ($query) {

                $query->where('room_status', request('room_status'));
            }, function ($query) {
                $query->where('room_status', Room::AVAILABLE_FOR_BOOKING);
            })
            ->when(request('room_number'), fn ($builder) => $builder->where('room_number', request('room_number')))
            ->where('hidden', false)
            // ->when(request('room_status'), fn ($query) => $query->where('room_status', request('room_status')))
            ->with(['images', 'students', 'reservations', 'tags', 'prices', 'allocations'])
            ->withCount(['reservations' => fn ($builder) => $builder->whereStatus(Reservation::STATUS_ACTIVE)])
            ->latest('id')
            ->paginate(20);

        //  dd(DB::getQueryLog());


        return RoomResource::collection(
            $rooms
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room): JsonResource
    {


        $room->loadCount(['reservations' => fn ($builder) => $builder->whereStatus(Reservation::STATUS_ACTIVE)])
            ->load(['images', 'tags', 'prices']);

        return RoomResource::make($room);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        //
    }
}
