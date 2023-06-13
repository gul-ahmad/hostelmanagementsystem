<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Image;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomPrices;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\File;


class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {

        // dd('dfdf');

        // Apply pagination
        $perPage = $request->input('perPage', 10);
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
            // ->when(request('room_status'), fn ($query) => $query->where('room_status', request('room_status')))
            ->with(['images', 'students', 'reservations', 'tags', 'prices', 'allocations'])
            ->withCount(['reservations' => fn ($builder) => $builder->whereStatus(Reservation::STATUS_ACTIVE)])
            ->latest('id')
            ->paginate($perPage, ['*'], 'currentPage', $page);

        //  dd(DB::getQueryLog());

        return response()->json([
            'rooms' => $rooms->items(),
            'totalPage' => $rooms->lastPage(),
            'totalRooms' => $rooms->total(),
        ]);

        // return RoomResource::collection(
        //     $rooms
        // );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateRoomRequest $request): JsonResource
    {
        // dd($request->all());

        $validated = $request->validated();
        $validated = array_merge($validated, ['available_slots' => $validated['capacity']]);

       

        DB::beginTransaction();

        try {

            $room = Room::create(


                Arr::except($validated, ['tags', 'prices', 'images'])

            );
            if (isset($validated['tags'])) {
                $room->tags()->attach($validated['tags']);
            }
            if (isset($validated['prices'])) {

                $room->prices()->create($validated['prices'][0]);
            }
            if (isset($validated['images'])) {

                foreach ($validated['images'] as $image) {

                    Storage::copy('public/filepondtmp/tmp/' . $image['folder'] . '/' . $image['file'], 'public/roomsfinal/tmp/' . $image['folder'] . '/' . $image['file']);
                    $realPath = $image['folder'] . '/' . $image['file'];
                    // Storage::deleteDirectory('/products/tmp/' .$tmp_file->folder);
                    Storage::deleteDirectory(('public/filepondtmp/tmp/') . $image['folder']);
                    // $image->delete();
                    $room->images()->create([
                        'path' => $realPath
                    ]);
                }
            }

            // return $room;

            DB::commit();

            return RoomResource::make(
                $room->load(['images', 'tags', 'prices'])
            );
        } catch (\Throwable $th) {
            Log::error('Error saving room price', ['error' => $th->getMessage()]);

            // Rethrow the exception to handle it in the catch block outside of this try-catch
            throw $th;
            DB::rollback();
            return response()->json(['message' => 'Error occurred while creating the room: ' . $th->getMessage()], 500);
        }
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
    public function show(Request $request, $id): JsonResponse
    {




        // $room->loadCount(['reservations' => fn ($builder) => $builder->whereStatus(Reservation::STATUS_ACTIVE)])
        //     ->load(['images', 'tags', 'prices']);

        // return RoomResource::make($room);

        $room = Room::query()
            ->where('room_number', $id)
            ->with(['images', 'students', 'reservations', 'tags', 'prices', 'allocations'])
            ->withCount(['reservations' => fn ($builder) => $builder->whereStatus(Reservation::STATUS_ACTIVE)])
            ->first();


        return response()->json([

            'room' => $room,

        ]);
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
    public function update(UpdateRoomRequest $request, Room $room): JsonResource
    {


        $validated = $request->validated();
        $validated = array_merge($validated, ['available_slots' => $validated['capacity']]);

        DB::beginTransaction();

        try {

            $room->update(

                Arr::except($validated, ['tags', 'prices'])

            );
            if (isset($validated['tags'])) {
                $room->tags()->sync($validated['tags']);
            }
            if (isset($validated['prices'])) {
                $room->prices()->delete();

                $room->prices()->create($validated['prices'][0]);
            }

            // return $room;

            DB::commit();

            return RoomResource::make(
                $room->load(['images', 'tags', 'prices'])
            );
        } catch (\Throwable $th) {
            Log::error('Error updating room price', ['error' => $th->getMessage()]);

            // Rethrow the exception to handle it in the catch block outside of this try-catch
            throw $th;
            DB::rollback();
            return response()->json(['message' => 'Error occurred while updating the room: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {

        throw_if(
            $room->reservations()->where('status', Reservation::STATUS_ACTIVE)->exists(),
            ValidationException::withMessages(['room' => 'Cannot delete this room!'])
        );
        $room->images()->each(function ($image) {

            $folder = dirname($image->path);
            $path = '/public/roomsfinal/tmp/' . $folder;
            Storage::deleteDirectory($path);
            Image::where('id', $image->id)->delete();
        });

        $room->delete();

        return response()->json('Room Deleted Successfully', 200);
    }



    public function checkAvailability(Request $request, $roomNumber)
    {
        //  dd($roomNumber);
        $room = Room::where('room_number', $roomNumber)->first();

        if ($room) {
            return response()->json(['available' => false]);
        } else {
            return response()->json(['available' => true]);
        }
    }
}
