<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/returns')->name('api.v1.returns.')->group(function () {
    Route::post('/', 'Api\V1\ReturnApiController@store')->name('store');
    Route::post('shipments/{uuid}', 'Api\V1\ReturnApiController@storeByShipmentUuid')->name('shipment.store');
    Route::get('{return}', 'Api\V1\ReturnApiController@show')->name('show');
    Route::post('{return}/attachments', 'Api\V1\ReturnApiController@upload')->name('attachments');
    Route::post('{return}/warehouse', 'Api\V1\ReturnApiController@markWarehouse')->name('warehouse');
    Route::post('{return}/close', 'Api\V1\ReturnApiController@close')->name('close');
});
