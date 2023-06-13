<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomFeatureImage;
use App\Http\Controllers\RoomImageDelete;
use App\Http\Controllers\RoomImageUpdate;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use PharIo\Manifest\AuthorCollectionIterator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['prefix' => 'auth'], function () {

    Route::post('/login', [AuthController::class, 'login']);


    Route::post('/register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function () {


        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('/user-list', [AuthController::class, 'user'])->where([
            'page' => '[0-9]+',
            'perPage' => '[0-9]+',
            'q' => 'string',
        ]);
        Route::patch('user/update/{id}', [AuthController::class, 'update']);
        Route::delete('user/delete/{id}', [AuthController::class, 'destroy']);
        //rooms
        Route::get('/rooms', [RoomController::class, 'index'])->where([
            'page' => '[0-9]+',
            'perPage' => '[0-9]+',
            'q' => 'string',
        ]);
        Route::get('/room/{id}', [RoomController::class, 'show']);
        Route::post('/rooms', [RoomController::class, 'create']);
        Route::put('/rooms/{room}', [RoomController::class, 'update']);
        Route::delete('room/delete/{room}', [RoomController::class, 'destroy']);

        Route::post('/filepond-upload', [ImageController::class, 'store']);
        Route::delete('filepond-delete', [ImageController::class, 'destroy']);

        Route::get('/tempimages', [ImageController::class, 'index']);

        Route::patch('/room/updateimage/{id}', RoomImageUpdate::class);
        Route::delete('/room/image/delete/{roomId}', RoomImageDelete::class);
        Route::post('/room/featuredimage', RoomFeatureImage::class);

        //Reservations 

        Route::post('/reservations', [UserReservationController::class, 'create']);
        Route::delete('/reservations/{reservation}', [UserReservationController::class, 'cancel']);
    });
});





//Tags
Route::get('/tags', TagController::class);

//Rooms


//Route::get('/room/{room}', [RoomController::class, 'show']);



Route::get('/rooms/check-availability/{roomNumber}', [RoomController::class, 'checkAvailability']);
