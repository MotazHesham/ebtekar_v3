<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('shipments', 'ShipmentApiController@index');
    Route::get('shipments/{shipment}', 'ShipmentApiController@show');
    Route::patch('shipments/{shipment}/status', 'ShipmentApiController@updateStatus');
    Route::post('courier/scan/lookup', 'CourierScanApiController@lookup');
    Route::post('courier/scan/apply', 'CourierScanApiController@apply');
    Route::get('partners/dashboard', 'ShippingPartnerApiController@dashboard');
});
