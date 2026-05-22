<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/dispatch')->name('api.v1.dispatch.')->group(function () {
    Route::post('assign', 'Api\V1\DispatchApiController@assign')->name('assign');
    Route::post('assign-bulk', 'Api\V1\DispatchApiController@assignBulk')->name('assign-bulk');
    Route::post('auto-assign', 'Api\V1\DispatchApiController@autoAssign')->name('auto-assign');
});
