<?php

use Illuminate\Support\Facades\Route;

Route::prefix('shipping/dashboard')->name('shipping.dashboard.')->group(function () {
    Route::get('/', 'PortalDashboardController@home')->name('home');
    Route::get('partner', 'PortalDashboardController@partner')->name('partner');
    Route::get('courier', 'PortalDashboardController@courier')->name('courier');
    Route::get('dispatcher', 'PortalDashboardController@dispatcher')->name('dispatcher');
    Route::get('admin', 'PortalDashboardController@admin')->name('admin');
});
