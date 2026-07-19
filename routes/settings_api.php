<?php

use App\Http\Controllers\Api\V1\Settings\SettingsController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Settings'], function () {

    Route::get('settings', [SettingsController::class, 'index']);
    Route::get('countries', [SettingsController::class, 'countries']);
    Route::get('payment-methods', [SettingsController::class, 'paymentMethods']);
    Route::post('contact-us', [SettingsController::class, 'contactUs']);
    Route::get('categories', [SettingsController::class, 'categories']);
});
