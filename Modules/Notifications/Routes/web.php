<?php

use Illuminate\Support\Facades\Route;

Route::get('notification-deliveries', 'NotificationLogWebController@index')->name('notification-deliveries.index');
