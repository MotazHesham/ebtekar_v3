<?php

use Illuminate\Support\Facades\Route;

Route::prefix('settlements')->name('settlements.')->group(function () {
    Route::get('preview', 'SettlementWebController@preview')->name('preview');
    Route::get('create', 'SettlementWebController@create')->name('create');
    Route::post('/', 'SettlementWebController@store')->name('store');
    Route::get('/', 'SettlementWebController@index')->name('index');
    Route::get('{settlement}', 'SettlementWebController@show')->name('show');
    Route::post('{settlement}/confirm', 'SettlementWebController@confirm')->name('confirm');
});
