<?php

use Illuminate\Support\Facades\Route;

Route::delete('deliver-men/destroy', 'CourierWebController@massDestroy')->name('deliver-men.massDestroy');
Route::resource('deliver-men', 'CourierWebController')->parameters(['deliver-men' => 'courier']);
