<?php

use Illuminate\Support\Facades\Route;

Route::get('inventory/items', 'ItemWebController@index')->name('inventory.items.index');
Route::get('inventory/items/create', 'ItemWebController@create')->name('inventory.items.create');
Route::post('inventory/items', 'ItemWebController@store')->name('inventory.items.store');
Route::get('inventory/items/{item}', 'ItemWebController@show')->name('inventory.items.show');
Route::put('inventory/items/{item}', 'ItemWebController@update')->name('inventory.items.update');
Route::delete('inventory/items/{item}', 'ItemWebController@destroy')->name('inventory.items.destroy');
Route::post('inventory/items/{item}/adjust', 'ItemWebController@adjust')->name('inventory.items.adjust');
Route::post('inventory/items/{item}/sync', 'ItemWebController@sync')->name('inventory.items.sync');

Route::get('inventory/social-links', 'SocialLinkWebController@index')->name('inventory.social-links.index');
Route::post('inventory/social-links', 'SocialLinkWebController@store')->name('inventory.social-links.store');

Route::get('inventory/shopify', 'ShopifyWebController@index')->name('inventory.shopify.index');
Route::post('inventory/shopify/link', 'ShopifyWebController@link')->name('inventory.shopify.link');
Route::post('inventory/shopify/location', 'ShopifyWebController@saveLocation')->name('inventory.shopify.location');

Route::get('inventory/unmapped', 'ShopifyWebController@unmapped')->name('inventory.unmapped.index');
Route::get('inventory/sync-failures', 'ShopifyWebController@failures')->name('inventory.sync-failures.index');
Route::post('inventory/sync-failures/{failure}/retry', 'ShopifyWebController@retry')->name('inventory.sync-failures.retry');
