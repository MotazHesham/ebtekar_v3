<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Http\Controllers\Shopify\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'shopify', 'as' => 'shopify.'], function () { 

    //Orders
    Route::controller(OrderController::class)->group(function () {
        Route::any('/{website_setting_id}/order/create', 'createOrUpdate')->name('order.create');
        // Route::any('/{website_setting_id}/order/update', 'createOrUpdate')->name('order.update');
        // Route::any('/{website_setting_id}/order/delete', 'delete')->name('order.delete');
    }); 

});