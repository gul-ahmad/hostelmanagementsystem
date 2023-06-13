<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomImageUpdateRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;

class RoomImageUpdate extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RoomImageUpdateRequest $request)
    {
       // $files = $request->input('room.roomImage.files');

       // dd($files);
        $validated = $request->validated();
       // dd($validated);
        DB::beginTransaction();

        try {

            $room = Room::findOrFail($validated['room']['roomImage']['roomId']);

            if (isset($validated['room']['roomImage']['files'])) {

                foreach ($validated['room']['roomImage']['files'] as $image) {

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
                $room->load(['images'])
            );
        } catch (\Throwable $th) {
            Log::error('Error saving room images', ['error' => $th->getMessage()]);

            // Rethrow the exception to handle it in the catch block outside of this try-catch
            throw $th;
            DB::rollback();
            return response()->json(['message' => 'Error occurred while adding new images to the room: ' . $th->getMessage()], 500);
        }
    }
}
