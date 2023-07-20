<?php

use App\Http\Controllers\API\V1\PermissionController;
use App\Http\Controllers\API\V1\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontEndRoomController;
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
        Route::put('/rooms/{id}', [RoomController::class, 'update']);
        Route::delete('room/delete/{room}', [RoomController::class, 'destroy']);

        Route::post('/filepond-upload', [ImageController::class, 'store']);
        Route::delete('filepond-delete', [ImageController::class, 'destroy']);

        Route::get('/tempimages', [ImageController::class, 'index']);

        Route::patch('/room/updateimage/{id}', RoomImageUpdate::class);
        Route::delete('/room/image/delete/{roomId}', RoomImageDelete::class);
        Route::post('/room/featuredimage', RoomFeatureImage::class);

        //Reservations 

        Route::get('/reservations', [UserReservationController::class, 'index']);
        Route::post('/reservations', [UserReservationController::class, 'create']);
        Route::delete('/reservations/{reservation}', [UserReservationController::class, 'cancel']);

        //Permissions


        Route::apiResource('permissions', PermissionController::class);

        //roles
        Route::get('roles', [RoleController::class, 'index']);
        Route::post('roles', [RoleController::class, 'store']);
        Route::get('roles/{role}', [RoleController::class, 'show']);
        Route::put('roles/{role}', [RoleController::class, 'update']);
        Route::delete('roles/{role}', [RoleController::class, 'destroy']);
    });
});





//Tags
Route::get('/tags', TagController::class);

//Rooms


Route::get('/rooms/frontend', FrontEndRoomController::class)->where([
    'page' => '[0-9]+',
    'perPage' => '[0-9]+',
    'q' => 'string',
]);

Route::get('/rooms/check-availability/{roomNumber}', [RoomController::class, 'checkAvailability']);
