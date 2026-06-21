<?php

use App\Http\Controllers\Api\V1\Customer\AddressController;
use App\Http\Controllers\Api\V1\Customer\AuthController;
use App\Http\Controllers\Api\V1\Customer\CartController;
use App\Http\Controllers\Api\V1\Customer\CatalogController;
use App\Http\Controllers\Api\V1\Customer\FavoriteController;
use App\Http\Controllers\Api\V1\Customer\ForgetPasswordController;
use App\Http\Controllers\Api\V1\Customer\HomeController;
use App\Http\Controllers\Api\V1\Customer\ProductController;
use App\Http\Controllers\Api\V1\Customer\ProfileController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'v1/customer', 'as' => 'api.', 'namespace' => 'Api\V1\Customer'], function () {

    // Authentication
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('register-otp', [AuthController::class, 'registerOtp']);
    Route::post('register-otp-resend', [AuthController::class, 'registerOtpResend']);
    Route::post('forgetpassword', [ForgetPasswordController::class, 'forgetpassword']);
    Route::post('forgetpassword-otp', [ForgetPasswordController::class, 'forgetpasswordOtp']);
    Route::post('forgetpassword-reset', [ForgetPasswordController::class, 'forgetpasswordReset']);

    // Home
    Route::group(['prefix' => 'home'], function () {
        Route::get('headline-bar', [HomeController::class, 'headlineBar']);
        Route::get('trust-badges', [HomeController::class, 'trustBadges']);
        Route::get('sliders', [HomeController::class, 'sliders']);
        Route::get('home-categories', [HomeController::class, 'homeCategories']);
        Route::get('best-selling-products', [HomeController::class, 'bestSellingProducts']);
        Route::get('new-products', [HomeController::class, 'newProducts']);
        Route::get('banner', [HomeController::class, 'banner']);
    });

    // Catalog
    Route::group(['prefix' => 'catalog'], function () {
        Route::get('products', [CatalogController::class, 'products']);
        Route::get('categories', [CatalogController::class, 'categories']);
    });

    // Product
    Route::group(['prefix' => 'product'], function () {
        Route::get('detail/{id}', [ProductController::class, 'detail']);
        Route::post('variant-price', [ProductController::class, 'variantPrice']);
        Route::get('related-products/{id}', [ProductController::class, 'relatedProducts']);
        Route::get('reviews/{id}', [ProductController::class, 'reviews']);
    });

    // Cart
    Route::group(['prefix' => 'cart'], function () {
        Route::post('add', [CartController::class, 'add']);
        Route::post('update-quantity', [CartController::class, 'updateQuantity']);
        Route::get('remove-item/{id}', [CartController::class, 'removeItem']);
        Route::get('remove-all-items', [CartController::class, 'removeAllItems']);
        Route::get('view', [CartController::class, 'view']);
        Route::get('items', [CartController::class, 'items']);
        Route::post('update-cart', [CartController::class, 'updateCart']);
        Route::post('checkout', [CartController::class, 'checkout']);
    });

    // Addresses
    Route::group(['prefix' => 'addresses'], function () {
        Route::get('list', [AddressController::class, 'list']);
        Route::post('add', [AddressController::class, 'add']);
        Route::post('update', [AddressController::class, 'update']);
        Route::get('set-default/{addressId}', [AddressController::class, 'setDefault']);
        Route::get('delete/{addressId}', [AddressController::class, 'delete']);
    });

    Route::group(['middleware' => ['auth:sanctum', 'customer-auth-api']], function () {

        Route::post('logout', [AuthController::class, 'logout']);

        // Favorites
        Route::group(['prefix' => 'favorites'], function () {
            Route::get('list', [FavoriteController::class, 'list']);
            Route::post('toggle', [FavoriteController::class, 'toggle']);
        });

        // Profile 
        Route::group(['prefix' => 'profile'], function () {
            Route::get('info', [ProfileController::class, 'info']);
            Route::post('update', [ProfileController::class, 'update']);
            Route::post('update-password', [ProfileController::class, 'updatePassword']);
            Route::post('delete-account', [ProfileController::class, 'deleteAccount']);
            Route::post('device-token', [ProfileController::class, 'deviceToken']);
        });
    });
});
