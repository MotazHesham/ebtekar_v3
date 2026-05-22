<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/tracking')->name('api.v1.tracking.')->group(function () {
    Route::post('scan/handoff', 'Api\V1\ScanApiController@handoff')->name('scan.handoff');
    Route::post('scan/receive', 'Api\V1\ScanApiController@receive')->name('scan.receive');
});
