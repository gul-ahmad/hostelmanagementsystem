<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Allocation;
use App\Models\Reservation;
use App\Models\Room;
use App\Notifications\NewUserReservation;
use Illuminate\Validation\Rule;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;



class UserReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        request()->validate([
            'status' => [Rule::in([Reservation::STATUS_ACTIVE, Reservation::STATUS_CANCELLED])],
            'room_id' => ['integer'],
            'from_date' => ['date', 'required_with:to_date'],
            'to_date' => ['date', 'required_with:from_date', 'after:from_date'],
        ]);

        $reservations = Reservation::query()
            ->when(
                request('room_id'),
                fn ($query) => $query->where('room_id', request('room_id'))
            )->when(
                request('from_date') && request('to_date'),
                fn ($query) => $query->betweenDates(request('from_date'), request('to_Date'))
            )->when(
                request('status'),
                fn ($query) => $query->where('status', request('status'))
            )->with(['room.featuredImage'])
            ->paginate(20);

        return ReservationResource::collection(

            $reservations
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd('I am here');


        $data = request()->validate([
            'room_id' => ['required', 'integer'],
            'start_date' => ['required', 'date:Y-m-d', 'after:today'],
            'end_date' => ['required', 'date:Y-m-d', 'after:start_date'],
            'reservation_type' => ['required', 'integer'], // 1: Whole room, 2: Three students
        ]);

        try {
            $room = Room::findOrFail($data['room_id']);
        } catch (ModelNotFoundException $e) {
            throw ValidationException::withMessages([
                'room_id' => 'Invalid Room Id'
            ]);
        }

        if ($room->available_slots == 0) {
            throw ValidationException::withMessages([

                'You cannot make a reservation on this room all seats booked',

            ]);
        }

        if ($room->hidden == 1  || $room->room_status == Room::NOT_AVAILABLE_FOR_BOOKING) {

            throw ValidationException::withMessages([
                'room_id' => 'You cannot make a reservation on a hidden office'
            ]);
        }
        if ($room->allocations()->where('slot1', true)->where('slot2', true)->where('slot3', true)->exists()) {

            throw ValidationException::withMessages([
                'room_id' => 'You cannot make a reservtion on this room ,all slots filled'
            ]);
        }

        // if ($room->reservations()->activeBetween(request('start_date'), request('end_date'))->exists()) {

        //     throw ValidationException::withMessages([
        //         'room_id' => 'You cannot make a reservation during this time'
        //     ]);
        // }


        $reservation = Cache::lock('reservation_room' . $room->id, 10)->block(3, function () use ($room, $data) {

            // if ($room->reservations()->ActiveBetween(request('start_date'), request('end_date'))->exists()) {

            //     throw ValidationException::withMessages([
            //         'room_id' => 'You cannot make a reservation during this time'
            //     ]);
            // }

            //  $availableSlots = $room->available_slots;
            $reservationType = $data['reservation_type'];

            // dd($reservationType);
            $price = 0;


            //  dd($availableSlots);

            if ($reservationType == 1 && $room->capacity == 1) {

                $price = $room->prices->price_for_one_person_booking;



                if ($room->available_slots == 1) {

                    // dd($room->allocations);
                    $room->allocations()->updateOrCreate(
                        [
                            'room_id' => $room->id,
                            'allocation_type' => $reservationType,

                        ],
                        [
                            'slot1' => true,
                            'slot2' => true,
                            'slot3' => true,

                        ],
                    );

                    $room->fill([
                        'available_slots' => 0,

                    ]);
                    $room->save();
                }
            } elseif ($reservationType == 2 && $room->capacity = 2) {

                // dd('type is 2');
                $price = $room->prices->price_for_two_person_booking;

                if ($room->available_slots == 2) {

                    // $room->allocations()->create([
                    //     'slot1' => true,
                    //     'allocation_type' => $reservationType,

                    // ]);
                    $room->allocations()->updateOrCreate(
                        [
                            'room_id' => $room->id,
                            'allocation_type' => $reservationType,
                        ],
                        [
                            'slot1' => true,
                        ],
                    );
                    $room->fill([
                        'available_slots' => 1,

                    ]);
                    $room->save();
                }
                //Note Gul here
                //In case of reservation type 2 we are filling slot2 and slot3 on second booking 
                //of the room(this is something which make confusion)
                elseif ($room->available_slots == 1) {

                    $room->allocations()->update([
                        'slot2' => true,
                        'slot3' => true,
                    ]);
                    $room->fill([
                        'available_slots' => 0,

                    ]);
                    $room->save();
                }
            } elseif ($reservationType == 3 && $room->capacity == 3) {

                // dd('type is 3');
                $price = $room->prices->price_for_three_person_booking;

                if ($room->available_slots == 3) {
                    // dd('slots are 3');

                    // $room->allocations()->create([
                    //     'slot1' => true,
                    //     'allocation_type' => $reservationType,

                    // ]);
                    $room->allocations()->updateOrCreate(
                        [
                            'room_id' => $room->id,
                            'allocation_type' => $reservationType,
                        ],
                        [
                            'slot1' => true,
                        ],
                    );
                    $room->fill([
                        'available_slots' => 2,
                    ]);

                    $room->save();
                } elseif ($room->available_slots == 2) {
                    //dd('slots are 2');

                    $room->allocations()->update([
                        'slot2' => true,
                    ]);
                    $room->fill([
                        'available_slots' => 1,

                    ]);
                    $room->save();
                } elseif ($room->available_slots == 1) {
                    $room->allocations()->update([
                        'slot3' => true,
                    ]);
                    $room->fill([
                        'available_slots' => 0,

                    ]);
                    $room->save();
                }
            } else {
                throw ValidationException::withMessages([
                    'room_id' => 'Invalid reservation type',
                ]);
            }

            if ($room->prices->discount_on_full_allocation > 0) {
                $price -= $room->prices->discount_on_full_allocation;
            }
            //  dd('dfdfdfd');
            return   Reservation::create([
                'room_id' => $room->id,
                'user_id' => auth()->user()->id,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => Reservation::STATUS_ACTIVE,
                'price' => $price,
                'wifi_password' => Str::random()
            ]);
        });

        //send notification to user

        Notification::send(auth()->user(), new NewUserReservation($reservation));

        return ReservationResource::make(
            $reservation->load('room')
        );
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
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        //
    }


    public function cancel(Reservation $reservation)
    {
        //dd($reservation->status);
        if ($reservation->user_id !== auth()->id()) {
            throw ValidationException::withMessages([
                'resservation' => 'You cannot delete this reservation',
            ]);
        }

        if ($reservation->status == Reservation::STATUS_CANCELLED || $reservation->start_date < now()->toDateString()) {
            throw ValidationException::withMessages([
                'resservation' => 'You cannot delete this reservation',
            ]);
        }

        //  dd($reservation);
        $roomAvailableSlot = $reservation->room->available_slots;
        $roomAllocations = $reservation->room->allocations;

        //  dd($roomAllocations);

        //dd($reservation->room->id);


        // $reservation->update([
        //     'status' => Reservation::STATUS_CANCELLED,
        // ]);




        if ($roomAllocations->allocation_type == 1 && $reservation->room->capacity == 1) {
            // dd('fgfgfgfg');
            $roomAllocations->update([

                'slot1' => false,
                'slot2' => false,
                'slot3' => false,

            ]);
            $roomAllocations->save();
            Reservation::where('id', $reservation->id)
                ->update(['status' => Reservation::STATUS_CANCELLED,]);
            Room::where('id', $reservation->room->id)
                ->update(['available_slots' => 1]);
        } elseif ($roomAllocations->allocation_type == 3 && $reservation->room->capacity == 3) {
            // dd('t3');

            if ($reservation->room->available_slots == 2) {
                // dd('slots are 2');

                $roomAllocations->update([
                    'slot1' => false,
                    'slot2' => false,
                    'slot3' => false,
                ]);
                // $roomAllocations->fill([
                //     'available_slots' => 3,

                // ]);
                // $roomAllocations->save();
                Reservation::where('id', $reservation->id)
                    ->update(['status' => Reservation::STATUS_CANCELLED,]);
                Room::where('id', $reservation->room->id)
                    ->update(['available_slots' => 3]);
            } elseif ($reservation->room->available_slots == 1) {
                // dd('slots are 1');
                $roomAllocations->update([
                    'slot2' => false,
                    'slot3' => false,
                ]);
                // $roomAllocations->fill([
                //     'available_slots' => 2,

                // ]);
                // $roomAllocations->save();
                Reservation::where('id', $reservation->id)
                    ->update(['status' => Reservation::STATUS_CANCELLED,]);
                Room::where('id', $reservation->room->id)
                    ->update(['available_slots' => 2]);
            } elseif ($reservation->room->available_slots == 0) {
                //  dd('slots are 0');
                $roomAllocations->update([
                    'slot3' => false,
                ]);
                // $roomAllocations->fill([
                //     'available_slots' => 2,

                // ]);
                // $roomAllocations->save();
                Reservation::where('id', $reservation->id)
                    ->update(['status' => Reservation::STATUS_CANCELLED,]);
                Room::where('id', $reservation->room->id)
                    ->update(['available_slots' => 1]);
            }
        } elseif ($roomAllocations->allocation_type == 2 && $reservation->room->capacity == 2) {


            if ($reservation->room->available_slots == 1) {
                //dd('slots are 2');

                $roomAllocations->update([
                    'slot1' => false,
                    'slot2' => false,
                    'slot3' => false,
                ]);
                // $roomAllocations->fill([
                //     'available_slots' => 3,

                // ]);
                // $roomAllocations->save();
                Reservation::where('id', $reservation->id)
                    ->update(['status' => Reservation::STATUS_CANCELLED,]);
                Room::where('id', $reservation->room->id)
                    ->update(['available_slots' => 2]);
            } elseif ($reservation->room->available_slots == 0) {
                $roomAllocations->update([
                    'slot2' => false,
                    'slot3' => false,
                ]);
                // $roomAllocations->fill([
                //     'available_slots' => 2,

                // ]);
                // $roomAllocations->save();
                Reservation::where('id', $reservation->id)
                    ->update(['status' => Reservation::STATUS_CANCELLED,]);
                Room::where('id', $reservation->room->id)
                    ->update(['available_slots' => 1]);
            }
        }

        return ReservationResource::make(

            $reservation->load('room')
        );
    }
}
