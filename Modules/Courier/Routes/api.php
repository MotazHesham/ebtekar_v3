<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('couriers', 'CourierApiController@index');
    Route::get('couriers/{courier:uuid}', 'CourierApiController@show');
});
