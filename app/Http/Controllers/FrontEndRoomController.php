<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;

class FrontEndRoomController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // dd('here');
        $perPage = $request->input('perPage', 15);
        $page = $request->input('currentPage', 1);


        //  DB::enableQueryLog();
        $rooms = Room::query()
            ->when(request('room_status'), function ($query) {

                $query->where('room_status', request('room_status'));
            }, function ($query) {
                $query->where('room_status', Room::AVAILABLE_FOR_BOOKING);
            })
            ->when(request('room_number'), fn ($builder) => $builder->where('room_number', request('room_number')))
            ->where('hidden', false)
            ->where('available_slots', '!=', 0)
            // ->when(request('room_status'), fn ($query) => $query->where('room_status', request('room_status')))
            ->with(['images', 'users', 'reservations', 'tags', 'prices', 'allocations'])
            ->withCount(['reservations' => fn ($builder) => $builder->whereStatus(Reservation::STATUS_ACTIVE)])
            ->latest('id')
            ->paginate($perPage, ['*'], 'currentPage', $page);

        //  dd(DB::getQueryLog());



        return response()->json([
            'rooms' => $rooms->items(),
            'totalPage' => $rooms->lastPage(),
            'totalRooms' => $rooms->total(),
        ]);
    }
}
