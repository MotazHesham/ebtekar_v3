<?php

use Illuminate\Support\Facades\Route;

Route::get('courier/scan', 'CourierScanWebController@index')->name('courier.scan');
Route::post('courier/scan/lookup', 'CourierScanWebController@lookup')->name('courier.scan.lookup');
Route::post('courier/scan/apply', 'CourierScanWebController@apply')->name('courier.scan.apply');
Route::delete('delivery-orders/destroy', 'ShipmentWebController@massDestroy')->name('delivery-orders.massDestroy');
Route::get('delivery-orders/stats', 'ShipmentWebController@stats')->name('delivery-orders.stats');
Route::post('delivery-orders/quick-status', 'ShipmentWebController@quickStatus')->name('delivery-orders.quick-status');
Route::post('delivery-orders/export', 'ShipmentWebController@export')->name('delivery-orders.export');
Route::post('delivery-orders/{shipment}/notes', 'ShipmentWebController@storeNote')->name('delivery-orders.notes.store');
Route::resource('delivery-orders', 'ShipmentWebController', ['except' => ['create', 'store', 'edit']])
    ->parameters(['delivery-orders' => 'shipment']);

Route::delete('shipping-partners/destroy', 'ShippingPartnerWebController@massDestroy')->name('shipping-partners.massDestroy');
Route::resource('shipping-partners', 'ShippingPartnerWebController');
