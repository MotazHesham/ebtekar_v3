<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Http\Controllers\Shopify\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'shopify', 'as' => 'shopify.'], function () { 

    //Orders
    Route::controller(OrderController::class)->group(function () {
        Route::any('/order/create', 'createOrUpdate')->name('order.create');
        Route::any('/order/update', 'createOrUpdate')->name('order.update');
        Route::any('/order/delete', 'delete')->name('order.delete');
    }); 

});