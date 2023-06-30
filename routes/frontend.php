<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\SubscriberController;
use App\Http\Controllers\Frontend\WishlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

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
    Route::get('be-seller',function(){return view('frontend.beseller');})->name('beseller');

    Route::get('product/{slug}', 'ProductController@product')->name('product'); 
    Route::post('product/quick_view', 'ProductController@quick_view')->name('product.quick_view');
    Route::post('product/variant_price', 'ProductController@variant_price')->name('product.variant_price');

    // search
    Route::get('/search', 'ProductController@search')->name('search'); 
    Route::get('/search?category={category_slug}', 'ProductController@search')->name('products.category');
    Route::get('/search?sub_category={subcategory_slug}', 'ProductController@search')->name('products.subcategory');
    Route::get('/search?sub_subcategory={subsubcategory_slug}', 'ProductController@search')->name('products.subsubcategory');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

        // checkout
        Route::get('payment_select', 'CheckoutController@payment_select')->name('payment_select');
        Route::post('checkout', 'CheckoutController@checkout')->name('checkout');

        // cart
        Route::get('cart', 'CartController@index')->name('cart');
        Route::post('cart/add', 'CartController@add')->name('cart.add');
        Route::post('cart/update', 'CartController@update')->name('cart.update');
        Route::post('cart/delete', 'CartController@delete')->name('cart.delete');


        // wishlist
        Route::get('/wishlist', 'WishlistController@wishlist')->name('wishlist');
        Route::get('/wishlist/add/{slug}', 'WishlistController@add')->name('wishlist.add');
        Route::post('wishlist/delete', 'WishlistController@delete')->name('wishlist.delete');

        // orders
        Route::get('/orders', 'OrderController@orders')->name('orders');
        Route::get('/orders/success/{id}', 'OrderController@success')->name('orders.success');
        Route::get('/orders/track/{id}', 'OrderController@track')->name('orders.track');

        // profile
        Route::post('/update_profile', 'ProfileController@update_profile')->name('update_profile');
        Route::post('/update_password', 'ProfileController@update_password')->name('update_password');
    });
});


