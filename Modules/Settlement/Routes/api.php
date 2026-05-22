<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/settlements')->name('api.v1.settlements.')->group(function () {
    Route::get('couriers/{courierId}/preview', 'Api\V1\SettlementApiController@previewToday')->name('courier.preview');
    Route::post('open', 'Api\V1\SettlementApiController@open')->name('open');
    Route::get('{settlement}', 'Api\V1\SettlementApiController@show')->name('show');
    Route::post('{settlement}/confirm', 'Api\V1\SettlementApiController@confirm')->name('confirm');
});
