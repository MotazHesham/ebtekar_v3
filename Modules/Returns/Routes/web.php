<?php

use Illuminate\Support\Facades\Route;

Route::prefix('returns')->name('returns.')->group(function () {
    Route::get('create', 'ReturnWebController@create')->name('create');
    Route::post('/', 'ReturnWebController@store')->name('store');
    Route::get('/', 'ReturnWebController@index')->name('index');
    Route::get('{return}/edit', 'ReturnWebController@edit')->name('edit');
    Route::get('{return}', 'ReturnWebController@show')->name('show');
    Route::put('{return}', 'ReturnWebController@update')->name('update');
    Route::delete('{return}', 'ReturnWebController@destroy')->name('destroy');
    Route::post('{return}/reopen', 'ReturnWebController@reopen')->name('reopen');
    Route::post('{return}/attachments', 'ReturnWebController@uploadAttachment')->name('attachments');
    Route::post('{return}/warehouse', 'ReturnWebController@markWarehouse')->name('warehouse');
    Route::post('{return}/close', 'ReturnWebController@close')->name('close');
});
