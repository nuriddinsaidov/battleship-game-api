<?php

/*
|--------------------------------------------------------------------------
| Service - API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for this service.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Prefix: /api/api
Route::group(['prefix' => ''], function() {

    Route::group(['prefix'=>'v1'], function() {

        Route::post('/game/start', 'gameController@start');

        Route::post('/game/{gameId}/shot', 'playerController@shot');

        Route::post('/game/{gameId}/receive-shot', 'playerController@receiveShot');

        Route::post('/game/grid/create', 'gridController@create');

        Route::post('/game/{gameId}/place-ship', 'gridController@placeShip');

    });

    Route::get('/', function() {
        return response()->json(['path' => '/api/api']);
    });


    
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });


});