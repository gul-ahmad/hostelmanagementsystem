<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class RoomImageDelete extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //  dd($request->all());

        $imagesIds = $request->imagesId;

        $images = DB::table('images')
            ->whereIn('id', $imagesIds)
            ->get();
        // $images = DB::table('images')
        //     ->where('resource_id', $request->roomId)
        //     ->get();
        $room = Room::findOrFail($request->roomId);

        //dd($images->count());
        throw_if(
            $room->images()->count() == 1 || count($imagesIds) == $room->images()->count(),
            ValidationException::withMessages(['image' => 'Cannot delete all the images one image must be left.'])
        );

        //  if ($images->count() == 1) {
        foreach ($images as $image) {

            $folder = dirname($image->path);
            //  dd($folder);
            $path = '/public/roomsfinal/tmp/' . $folder;

            //   dd($path);


            //  File::deleteDirectory($path);
            Storage::deleteDirectory($path);
            Image::where('id', $image->id)->delete();
        }
        return response()->json(['message' => 'Images deleted successfully'], 200);
        //  } else {
        //  return response()->json(['message' => 'At least one image must remain in the room'], 422);
        //  }
    }
}
