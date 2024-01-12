<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix'=>'admin'], function(){
    Route::post('/login', [AdminController::class, 'login']);
});

Route::group(['prefix'=>'station'], function(){
    Route::get('/get', [StationController::class, 'get']);
    Route::post('/store', [StationController::class, 'store']);
    Route::post('/edit/{id}', [StationController::class, 'edit']);
    Route::get('/get/{id}', [StationController::class, 'getById']);
    Route::delete('/delete/{id}', [StationController::class, 'delete']);

    Route::get('/getNearByStations', [StationController::class, 'getNearByStations']);
});

Route::group(['prefix'=>'review'], function(){
    Route::get('/get/{station_id}', [ReviewController::class, 'getReviewByStationId']);
    Route::post('/store', [ReviewController::class, 'store']);
});
