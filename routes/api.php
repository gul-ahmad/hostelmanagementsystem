<?php

use App\Http\Controllers\AuthController;
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
    });
});
//Route::get('/user-list', [AuthController::class, 'user']);
