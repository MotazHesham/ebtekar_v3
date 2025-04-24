<?php

use Illuminate\Support\Facades\Route;

Route::post('/track-event', [App\Http\Controllers\TrackingController::class, 'trackEvent']);
