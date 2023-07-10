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

/*
    fixing old db to newer
*/

// disable the observers first
Route::get('mixed','TransferDatabaseController@mixed');
// transfer the attributes manualy 
// transfer the social manualy  and the social_receipt_social
Route::get('transfer_users','TransferDatabaseController@transfer_users');
Route::get('transfer_categories','TransferDatabaseController@transfer_categories');
Route::get('transfer_sub_categories','TransferDatabaseController@transfer_sub_categories');
Route::get('transfer_sub_sub_categories','TransferDatabaseController@transfer_sub_sub_categories');
Route::get('transfer_products','TransferDatabaseController@transfer_products');
Route::get('transfer_products_stock','TransferDatabaseController@transfer_products_stock');
Route::get('transfer_orders','TransferDatabaseController@transfer_orders');
Route::get('transfer_orders_details','TransferDatabaseController@transfer_orders_details');
Route::get('transfer_commission_request','TransferDatabaseController@transfer_commission_request');
Route::get('receipt_products','TransferDatabaseController@receipt_products');
Route::get('receipt_clients','TransferDatabaseController@receipt_clients');
Route::get('receipt_client_products','TransferDatabaseController@receipt_client_products');
Route::get('receipt_socials','TransferDatabaseController@receipt_socials');
Route::get('receipt_socials_socials','TransferDatabaseController@receipt_socials_socials');
Route::get('receipt_social_products','TransferDatabaseController@receipt_social_products');
Route::get('receipt_outgoings','TransferDatabaseController@receipt_outgoings'); 
Route::get('receipt_price_view','TransferDatabaseController@receipt_price_view'); 
Route::get('receipt_companies','TransferDatabaseController@receipt_companies'); 
// return the observers on
// empty autdit logs








Route::get('fix_receipt_client_product',function(){
    // add receipt_product_id column
    $data = \App\Models\ReceiptClientProduct::all();
    foreach($data as $row){
        $receipt_product = \App\Models\ReceiptSocialProduct::where('name',$row->description)->first();
        if($receipt_product){
            $row->receipt_product_id = $receipt_product->id;
            $row->save();
        }
    }
    return 1;
});
Route::get('fix_products',function(){
    $products = \App\Models\Product::get();

    foreach($products as $product){

        // change from attribute_id to attribute
            // if($product->choice_options != null){
                // $product->choice_options = str_replace('attribute_id','attribute',$product->choice_options);
                // $choice_options = json_decode($product->choice_options) ;
                // foreach ($choice_options as $key => $choice){
                //     if(\App\Models\Attribute::find($choice->attribute)){
                //         $choice_options[$key]->attribute = \App\Models\Attribute::find($choice->attribute)->name;
                //     }
                //     $product->choice_options = json_encode($choice_options);
                // }
            // }
        // ---------

        // change from amount to flat
            if($product->discount_type == 'amount'){
                $product->discount_type = 'flat';
            }
        // ---------
        $product->save();
    }
    return 1;
});


Route::get('/', 'Frontend\HomeController@index')->name('home');

// subscribers

Route::group(['as' => 'frontend.', 'namespace' => 'Frontend'], function () {

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
    Route::post('product/quick_view', 'ProductController@quick_view')->name('product.quick_view');
    Route::post('product/variant_price', 'ProductController@variant_price')->name('product.variant_price');
    Route::post('product/rate_product', 'ProductController@rate_product')->name('product.rate');

    // search
    Route::get('search', 'ProductController@search')->name('search'); 
    Route::get('search?category={category_slug}', 'ProductController@search')->name('products.category');
    Route::get('search?sub_category={subcategory_slug}', 'ProductController@search')->name('products.subcategory');
    Route::get('search?sub_subcategory={subsubcategory_slug}', 'ProductController@search')->name('products.subsubcategory');

    // cart
    Route::get('cart', 'CartController@index')->name('cart');
    Route::post('cart/add', 'CartController@add')->name('cart.add');
    Route::post('cart/update', 'CartController@update')->name('cart.update');
    Route::get('cart/delete/{id}', 'CartController@delete')->name('cart.delete');
    
    // checkout
    Route::get('payment_select', 'CheckoutController@payment_select')->name('payment_select');
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
        Route::get('mockups/categories','mockupController@categories')->name('mockups.categories');
        Route::get('mockups/categories/{category_id}','mockupController@mockups')->name('mockups');  

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


