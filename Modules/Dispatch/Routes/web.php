<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dispatch')->name('dispatch.')->group(function () {
    Route::get('/', 'DispatchWebController@index')->name('index');
    Route::post('assign', 'DispatchWebController@assign')->name('assign');
    Route::post('assign-bulk', 'DispatchWebController@assignBulk')->name('assign-bulk');
    Route::post('auto-assign', 'DispatchWebController@autoAssign')->name('auto-assign');
});
