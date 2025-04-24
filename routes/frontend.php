<?php

use Illuminate\Support\Facades\Route; 
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| 
*/


Route::get('/', 'Frontend\HomeController@index')->name('home');
Route::get('/sitemap.xml', 'Frontend\HomeController@sitemap')->name('sitemap');

// Home Sections
Route::post('sections/new_products', 'Frontend\HomeController@new_products')->name('frontend.sections.new_products');
Route::post('sections/featured_categories', 'Frontend\HomeController@featured_categories')->name('frontend.sections.featured_categories');
Route::post('sections/best_selling_products', 'Frontend\HomeController@best_selling_products')->name('frontend.sections.best_selling_products');

// nafzly callback payment
// Route::get('/payments/verify/{payment?}',[FrontController::class,'payment_verify'])->name('payment-verify');

Route::get('paymob/callback','PayMobController@processedCallback'); // my own callback for payment


Route::group(['as' => 'frontend.', 'namespace' => 'Frontend'], function () {

    Route::get('pageview/event', 'HomeController@pageViewEvent')->name('pageViewEvent');
    Route::get('webxr/{id}', 'HomeController@webxr')->name('webxr');
    Route::get('about', 'HomeController@about')->name('about');
    Route::get('events', 'HomeController@events')->name('events');
    Route::get('policies/{policy}', 'HomeController@policies')->name('policies');
    Route::get('contact', 'HomeController@contact')->name('contact');
    Route::post('contact', 'HomeController@contact_store')->name('contact.store');
    Route::post('subscribe', 'SubscriberController@subscribe')->name('subscribe'); 

    // seller
    Route::get('be-seller', 'SellerController@beseller')->name('beseller');
    Route::post('be-seller', 'SellerController@register')->name('seller.register');

    // designer
    Route::get('be-designer', 'DesignerController@bedesigner')->name('bedesigner');
    Route::post('be-designer', 'DesignerController@register')->name('designer.register');

    Route::get('product/{slug}', 'ProductController@product')->name('product'); 
    Route::get('en_product/{id}', 'ProductController@en_product')->name('en_product'); 
    Route::post('product/quick_view', 'ProductController@quick_view')->name('product.quick_view');
    Route::post('product/variant_price', 'ProductController@variant_price')->name('product.variant_price');
    Route::post('product/rate_product', 'ProductController@rate_product')->name('product.rate');

    // search
    Route::get('search', 'ProductController@search')->name('search'); 
    Route::get('search?category={category_slug}', 'ProductController@search')->name('products.category');
    Route::get('search?sub_category={subcategory_slug}', 'ProductController@search')->name('products.subcategory');
    Route::get('search?sub_sub_category={subsubcategory_slug}', 'ProductController@search')->name('products.subsubcategory');

    // cart
    Route::get('cart', 'CartController@index')->name('cart');
    Route::post('cart/edit', 'CartController@edit')->name('cart.edit');
    Route::post('cart/add', 'CartController@add')->name('cart.add');
    Route::post('cart/update', 'CartController@update')->name('cart.update');
    Route::get('cart/delete/{id}', 'CartController@delete')->name('cart.delete');
    
    // checkout
    Route::get('payment_select', 'CheckoutController@payment_select')->name('payment_select');
    Route::post('checkout-summary', 'CheckoutController@checkout_summary')->name('checkout.summary');
    Route::post('checkout', 'CheckoutController@checkout')->name('checkout');

    // orders track
    Route::get('orders/success/{id}', 'OrderController@success')->name('orders.success');
    Route::get('orders/track/{id}', 'OrderController@track')->name('orders.track');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('dashboard', 'ProfileController@dashboard')->name('dashboard'); 

        //designer images 
        Route::get('designer-images/delete/{id}', 'DesignerImageController@delete')->name('designer-images.delete');
        Route::resource('designer-images','DesignerImageController');

        //design 
        Route::get('designs/start/{id}', 'DesignController@start')->name('designs.start');
        Route::get('designs/destroy/{id}', 'DesignController@destroy')->name('designs.destroy');
        Route::resource('designs','DesignController');

        //mockups
        Route::get('mockups/categories','MockupController@categories')->name('mockups.categories');
        Route::get('mockups/categories/{category_id}','MockupController@mockups')->name('mockups');  

        // wishlist
        Route::get('wishlist', 'WishlistController@wishlist')->name('wishlist');
        Route::get('wishlist/add/{slug}', 'WishlistController@add')->name('wishlist.add');
        Route::post('wishlist/delete', 'WishlistController@delete')->name('wishlist.delete');

        // orders
        Route::get('orders', 'OrderController@orders')->name('orders');
        Route::get('commission_requests', 'OrderController@commission_requests')->name('orders.commission_requests');
        Route::post('request_commission', 'OrderController@request_commission')->name('orders.request_commission.store');

        // profile
        Route::post('update_profile', 'ProfileController@update_profile')->name('update_profile');
        Route::post('update_password', 'ProfileController@update_password')->name('update_password');
    });
});


