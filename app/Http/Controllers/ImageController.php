<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\TemporaryFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;



class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        // dd($request->all());
        $image = $request->file('test');
        $folderRoom = uniqid('room', true);

        $path = public_path('rooms/tmp/') . $folderRoom;

        // if (!is_dir($path)) {
        //     mkdir($path, 0777, true);
        // }

        if ($request->file('test')) {



            $file_name = $image->getClientOriginalName();

            $image->move($path, $file_name);

            // $image->move($path . $folderRoom, $file_name);

            TemporaryFile::create([
                'folder' => $folderRoom,
                'file' => $file_name
            ]);

            //  return response()->json(['folderName' => $folderRoom]);
        }

        // Return an error response if the request doesn't contain a file
        // return response()->json(['error' => 'No file uploaded.'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // $folder = json_decode($request->getContent()); // folder name

        // $fileId = request()->getContent();
        $fileId = json_decode($request->getContent())->folderName;
        dd($fileId);
        $roomImages = Str::startsWith($fileId, 'room');



        if ($roomImages) {
            $tmp_file = TemporaryFile::where('folder', $fileId)->first();

            dd($tmp_file);
            if ($tmp_file) {
                //  Storage::deleteDirectory('/products/tmp/' .$tmp_file->folder);
                File::deleteDirectory(public_path('/rooms/tmp/') . $tmp_file->folder);
                $tmp_file->delete();
            }
        }
    }
}
