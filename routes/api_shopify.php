<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Http\Controllers\Shopify\OrderController;
use App\Http\Controllers\Shopify\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'shopify', 'as' => 'shopify.'], function () { 

    //Orders
    Route::controller(OrderController::class)->group(function () {
        Route::any('/{website_setting_id}/order/create', 'createOrUpdate')->name('order.create');
        Route::any('/{website_setting_id}/order/update', 'createOrUpdate')->name('order.update');
        Route::any('/{website_setting_id}/order/delete', 'delete')->name('order.delete');
    }); 

    //Products
    Route::controller(ProductController::class)->group(function () {
        Route::any('/{website_setting_id}/product/create', 'createOrUpdate')->name('product.create');
        Route::any('/{website_setting_id}/product/update', 'createOrUpdate')->name('product.update');
        // Route::any('/{website_setting_id}/product/delete', 'delete')->name('product.delete');
    });

});