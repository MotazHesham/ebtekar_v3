<?php

use Illuminate\Support\Facades\Route;

Route::delete('delivery-orders/destroy', 'ShipmentWebController@massDestroy')->name('delivery-orders.massDestroy');
Route::post('delivery-orders/{shipment}/notes', 'ShipmentWebController@storeNote')->name('delivery-orders.notes.store');
Route::resource('delivery-orders', 'ShipmentWebController', ['except' => ['create', 'store', 'edit']])
    ->parameters(['delivery-orders' => 'shipment']);

Route::delete('shipping-partners/destroy', 'ShippingPartnerWebController@massDestroy')->name('shipping-partners.massDestroy');
Route::resource('shipping-partners', 'ShippingPartnerWebController');
