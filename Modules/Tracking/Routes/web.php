<?php

use Illuminate\Support\Facades\Route;

Route::prefix('tracking')->name('tracking.')->group(function () {
    Route::get('scan/receive', 'ScanWebController@receiveScanner')->name('scan.receive.page');
    Route::post('scan/receive', 'ScanWebController@receiveOutput')->name('scan.receive');
});
