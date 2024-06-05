<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Route::get('magic_trick','Admin\HomeController@magic_trick')->name('magic_trick');
Route::post('magic_trick_store','Admin\HomeController@magic_trick_store')->name('magic_trick_store');


// related to push notification via firebase
Route::post('/save-token', 'PushNotificationController@saveToken')->name('save-token');  

Route::get('userVerification/{token}', 'UserVerificationController@approve')->name('userVerification');
Route::get('user/verify','UserVerificationController@verify')->name('user.verify');
Route::post('verification/resend','UserVerificationController@resend')->name('verification.resend');
Auth::routes();

//social - login
Route::get('/social-login/redirect/{provider}', 'Auth\LoginController@redirectToProvider')->name('social.login');
Route::get('/social-login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth','staff']], function () { 
    
    Route::get('transfer', 'HomeController@transfer')->name('z');
    Route::get('/', 'HomeController@index')->name('home');
    Route::post('search_by_phone', 'HomeController@search_by_phone')->name('search_by_phone');
    Route::post('receipts_logs', 'HomeController@receipts_logs')->name('receipts_logs');
    Route::post('show_qr_code', 'HomeController@show_qr_code')->name('show_qr_code');


    Route::get('qr_scanner/{type}','HomeController@qr_scanner')->name('qr_scanner');
    Route::post('qr_output','HomeController@qr_output')->name('qr_output');
    Route::get('barcode_scanner/{type}','HomeController@barcode_scanner')->name('barcode_scanner');
    Route::post('bar_code_output','HomeController@bar_code_output')->name('bar_code_output');
    
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/update_statuses', 'UsersController@update_statuses')->name('users.update_statuses');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Task Status
    Route::delete('task-statuses/destroy', 'TaskStatusController@massDestroy')->name('task-statuses.massDestroy');
    Route::resource('task-statuses', 'TaskStatusController');

    // Task Tag
    Route::delete('task-tags/destroy', 'TaskTagController@massDestroy')->name('task-tags.massDestroy');
    Route::resource('task-tags', 'TaskTagController');

    // Task
    Route::delete('tasks/destroy', 'TaskController@massDestroy')->name('tasks.massDestroy');
    Route::post('tasks/media', 'TaskController@storeMedia')->name('tasks.storeMedia');
    Route::post('tasks/ckmedia', 'TaskController@storeCKEditorImages')->name('tasks.storeCKEditorImages');
    Route::resource('tasks', 'TaskController');

    // Tasks Calendar
    Route::resource('tasks-calendars', 'TasksCalendarController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/history', 'UserAlertsController@history')->name('user-alerts.history');
    Route::get('user-alerts/playlist', 'UserAlertsController@playlist')->name('user-alerts.playlist');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Receipt Social
    Route::delete('receipt-socials/destroy_product/{id}', 'ReceiptSocialController@destroy_product')->name('receipt-socials.destroy_product');
    Route::get('receipt-socials/restore/{id}', 'ReceiptSocialController@restore')->name('receipt-socials.restore');
    Route::get('receipt-socials/print/{id}', 'ReceiptSocialController@print')->name('receipt-socials.print');
    Route::get('receipt-socials/receive_money/{id}', 'ReceiptSocialController@receive_money')->name('receipt-socials.receive_money');
    Route::get('receipt-socials/duplicate/{id}', 'ReceiptSocialController@duplicate')->name('receipt-socials.duplicate');
    Route::post('receipt-socials/upload_fedex', 'ReceiptSocialController@upload_fedex')->name('receipt-socials.upload_fedex');
    Route::post('receipt-socials/send_to_wasla', 'ReceiptSocialController@send_to_wasla')->name('receipt-socials.send_to_wasla');
    Route::post('receipt-socials/update_delivery_man', 'ReceiptSocialController@update_delivery_man')->name('receipt-socials.update_delivery_man');
    Route::post('receipt-socials/update_statuses', 'ReceiptSocialController@update_statuses')->name('receipt-socials.update_statuses');
    Route::post('receipt-socials/view_products', 'ReceiptSocialController@view_products')->name('receipt-socials.view_products');
    Route::post('receipt-socials/add_product', 'ReceiptSocialController@add_product')->name('receipt-socials.add_product');
    Route::post('receipt-socials/edit_product', 'ReceiptSocialController@edit_product')->name('receipt-socials.edit_product');
    Route::delete('receipt-socials/destroy', 'ReceiptSocialController@massDestroy')->name('receipt-socials.massDestroy');
    Route::resource('receipt-socials', 'ReceiptSocialController');

    // Receipt Social Product
    Route::delete('receipt-social-products/destroy', 'ReceiptSocialProductController@massDestroy')->name('receipt-social-products.massDestroy');
    Route::post('receipt-social-products/media', 'ReceiptSocialProductController@storeMedia')->name('receipt-social-products.storeMedia');
    Route::post('receipt-social-products/ckmedia', 'ReceiptSocialProductController@storeCKEditorImages')->name('receipt-social-products.storeCKEditorImages');
    Route::post('receipt-social-products/products_report', 'ReceiptSocialProductController@products_report')->name('receipt-social-products.products_report');
    Route::resource('receipt-social-products', 'ReceiptSocialProductController');

    // Receipt Client
    Route::delete('receipt-clients/destroy_product/{id}', 'ReceiptClientController@destroy_product')->name('receipt-clients.destroy_product');
    Route::get('receipt-clients/restore/{id}', 'ReceiptClientController@restore')->name('receipt-clients.restore');
    Route::get('receipt-clients/print/{id}', 'ReceiptClientController@print')->name('receipt-clients.print');
    Route::get('receipt-clients/receive_money/{id}', 'ReceiptClientController@receive_money')->name('receipt-clients.receive_money');
    Route::get('receipt-clients/duplicate/{id}', 'ReceiptClientController@duplicate')->name('receipt-clients.duplicate');
    Route::post('receipt-clients/update_statuses', 'ReceiptClientController@update_statuses')->name('receipt-clients.update_statuses');
    Route::post('receipt-clients/view_products', 'ReceiptClientController@view_products')->name('receipt-clients.view_products');
    Route::post('receipt-clients/add_product', 'ReceiptClientController@add_product')->name('receipt-clients.add_product');
    Route::post('receipt-clients/edit_product', 'ReceiptClientController@edit_product')->name('receipt-clients.edit_product');
    Route::delete('receipt-clients/destroy', 'ReceiptClientController@massDestroy')->name('receipt-clients.massDestroy');
    Route::resource('receipt-clients', 'ReceiptClientController');

    // Receipt Client Product
    Route::delete('receipt-client-products/destroy', 'ReceiptClientProductController@massDestroy')->name('receipt-client-products.massDestroy');
    Route::resource('receipt-client-products', 'ReceiptClientProductController');

    // Receipt Branch
    Route::delete('receipt-branches/destroy_product/{id}', 'ReceiptBranchController@destroy_product')->name('receipt-branches.destroy_product');
    Route::get('receipt-branches/restore/{id}', 'ReceiptBranchController@restore')->name('receipt-branches.restore');
    Route::get('receipt-branches/print/{id}', 'ReceiptBranchController@print')->name('receipt-branches.print');
    Route::get('receipt-branches/receive_money/{id}', 'ReceiptBranchController@receive_money')->name('receipt-branches.receive_money');
    Route::get('receipt-branches/duplicate/{id}', 'ReceiptBranchController@duplicate')->name('receipt-branches.duplicate');
    Route::post('receipt-branches/update_statuses', 'ReceiptBranchController@update_statuses')->name('receipt-branches.update_statuses');
    Route::post('receipt-branches/view_products', 'ReceiptBranchController@view_products')->name('receipt-branches.view_products');
    Route::post('receipt-branches/add_product', 'ReceiptBranchController@add_product')->name('receipt-branches.add_product');
    Route::post('receipt-branches/edit_product', 'ReceiptBranchController@edit_product')->name('receipt-branches.edit_product');
    Route::post('receipt-branches/permission_status', 'ReceiptBranchController@permission_status')->name('receipt-branches.permission_status');
    Route::post('receipt-branches/add_income', 'ReceiptBranchController@add_income')->name('receipt-branches.add_income');
    Route::post('receipt-branches/branches', 'ReceiptBranchController@branches')->name('receipt-branches.branches');
    Route::delete('receipt-branches/destroy', 'ReceiptBranchController@massDestroy')->name('receipt-branches.massDestroy');
    Route::resource('receipt-branches', 'ReceiptBranchController');

    // Receipt Branch Product
    Route::delete('receipt-branch-products/destroy', 'ReceiptBranchProductController@massDestroy')->name('receipt-branch-products.massDestroy');
    Route::resource('receipt-branch-products', 'ReceiptBranchProductController');

    // Receipt Company
    Route::get('receipt-companies/restore/{id}', 'ReceiptCompanyController@restore')->name('receipt-companies.restore');
    Route::get('receipt-companies/print/{id}', 'ReceiptCompanyController@print')->name('receipt-companies.print');
    Route::get('receipt-companies/duplicate/{id}', 'ReceiptCompanyController@duplicate')->name('receipt-companies.duplicate');
    Route::post('receipt-companies/send_to_wasla', 'ReceiptCompanyController@send_to_wasla')->name('receipt-companies.send_to_wasla');
    Route::post('receipt-companies/update_delivery_man', 'ReceiptCompanyController@update_delivery_man')->name('receipt-companies.update_delivery_man');
    Route::post('receipt-companies/update_statuses', 'ReceiptCompanyController@update_statuses')->name('receipt-companies.update_statuses');
    Route::delete('receipt-companies/destroy', 'ReceiptCompanyController@massDestroy')->name('receipt-companies.massDestroy');
    Route::post('receipt-companies/media', 'ReceiptCompanyController@storeMedia')->name('receipt-companies.storeMedia');
    Route::post('receipt-companies/ckmedia', 'ReceiptCompanyController@storeCKEditorImages')->name('receipt-companies.storeCKEditorImages');
    Route::resource('receipt-companies', 'ReceiptCompanyController');

    // Website Settings
    Route::post('website-settings/get_categories_by_website', 'WebsiteSettingsController@get_categories_by_website')->name('website-settings.get_categories_by_website');
    Route::post('website-settings/get_sub_categories_by_website', 'WebsiteSettingsController@get_sub_categories_by_website')->name('website-settings.get_sub_categories_by_website');
    Route::post('website-settings/media', 'WebsiteSettingsController@storeMedia')->name('website-settings.storeMedia');
    Route::post('website-settings/ckmedia', 'WebsiteSettingsController@storeCKEditorImages')->name('website-settings.storeCKEditorImages');
    Route::resource('website-settings', 'WebsiteSettingsController', ['except' => ['destroy']]);

    // Customers
    Route::delete('customers/destroy', 'CustomersController@massDestroy')->name('customers.massDestroy');
    Route::resource('customers', 'CustomersController');

    // Sellers
    Route::delete('sellers/destroy', 'SellersController@massDestroy')->name('sellers.massDestroy');
    Route::post('sellers/media', 'SellersController@storeMedia')->name('sellers.storeMedia');
    Route::post('sellers/ckmedia', 'SellersController@storeCKEditorImages')->name('sellers.storeCKEditorImages');
    Route::resource('sellers', 'SellersController');

    // Commission Requests
    Route::delete('commission-requests/destroy', 'CommissionRequestsController@massDestroy')->name('commission-requests.massDestroy');
    Route::resource('commission-requests', 'CommissionRequestsController'); 

    // Employees set password
    Route::post('employees/access', 'EmployeesController@access')->name('employees.access');
    
    Route::group(['middleware' => 'access_employee'], function () { 
        // Employees
        Route::delete('employees/destroy', 'EmployeesController@massDestroy')->name('employees.massDestroy');
        Route::resource('employees', 'EmployeesController'); 

        // Financial Category
        Route::delete('financial-categories/destroy', 'FinancialCategoryController@massDestroy')->name('financial-categories.massDestroy');
        Route::resource('financial-categories', 'FinancialCategoryController');
        
        // Employee Financial
        Route::delete('employee-financials/destroy', 'EmployeeFinancialController@massDestroy')->name('employee-financials.massDestroy');
        Route::resource('employee-financials', 'EmployeeFinancialController');
    });

    
    // Countries
    Route::delete('countries/destroy', 'CountriesController@massDestroy')->name('countries.massDestroy');
    Route::post('countries/update_statuses', 'CountriesController@update_statuses')->name('countries.update_statuses');
    Route::resource('countries', 'CountriesController');

    // Socials
    Route::delete('socials/destroy', 'SocialsController@massDestroy')->name('socials.massDestroy');
    Route::post('socials/media', 'SocialsController@storeMedia')->name('socials.storeMedia');
    Route::post('socials/ckmedia', 'SocialsController@storeCKEditorImages')->name('socials.storeCKEditorImages');
    Route::resource('socials', 'SocialsController');

    // Banned Phones
    Route::delete('banned-phones/destroy', 'BannedPhonesController@massDestroy')->name('banned-phones.massDestroy');
    Route::resource('banned-phones', 'BannedPhonesController');

    // Polices
    Route::delete('polices/destroy', 'PolicesController@massDestroy')->name('polices.massDestroy');
    Route::post('polices/media', 'PolicesController@storeMedia')->name('polices.storeMedia');
    Route::post('polices/ckmedia', 'PolicesController@storeCKEditorImages')->name('polices.storeCKEditorImages');
    Route::resource('polices', 'PolicesController');

    // Sliders
    Route::delete('sliders/destroy', 'SlidersController@massDestroy')->name('sliders.massDestroy');
    Route::post('sliders/update_statuses', 'SlidersController@update_statuses')->name('sliders.update_statuses');
    Route::post('sliders/media', 'SlidersController@storeMedia')->name('sliders.storeMedia');
    Route::post('sliders/ckmedia', 'SlidersController@storeCKEditorImages')->name('sliders.storeCKEditorImages');
    Route::resource('sliders', 'SlidersController');

    // Banners
    Route::delete('banners/destroy', 'BannersController@massDestroy')->name('banners.massDestroy');
    Route::post('banners/update_statuses', 'BannersController@update_statuses')->name('banners.update_statuses');
    Route::post('banners/media', 'BannersController@storeMedia')->name('banners.storeMedia');
    Route::post('banners/ckmedia', 'BannersController@storeCKEditorImages')->name('banners.storeCKEditorImages');
    Route::resource('banners', 'BannersController');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::post('categories/update_statuses', 'CategoriesController@update_statuses')->name('categories.update_statuses');
    Route::post('categories/media', 'CategoriesController@storeMedia')->name('categories.storeMedia');
    Route::post('categories/ckmedia', 'CategoriesController@storeCKEditorImages')->name('categories.storeCKEditorImages');
    Route::resource('categories', 'CategoriesController');

    // Home Categories
    Route::delete('home-categories/destroy', 'HomeCategoriesController@massDestroy')->name('home-categories.massDestroy');
    Route::resource('home-categories', 'HomeCategoriesController');

    // Quality Responsible
    Route::delete('quality-responsibles/destroy', 'QualityResponsibleController@massDestroy')->name('quality-responsibles.massDestroy');
    Route::post('quality-responsibles/media', 'QualityResponsibleController@storeMedia')->name('quality-responsibles.storeMedia');
    Route::post('quality-responsibles/ckmedia', 'QualityResponsibleController@storeCKEditorImages')->name('quality-responsibles.storeCKEditorImages');
    Route::resource('quality-responsibles', 'QualityResponsibleController');

    // Currencies
    Route::delete('currencies/destroy', 'CurrenciesController@massDestroy')->name('currencies.massDestroy');
    Route::post('currencies/update_statuses', 'CurrenciesController@update_statuses')->name('currencies.update_statuses');
    Route::resource('currencies', 'CurrenciesController');
    
    // Conversations
    Route::delete('conversations/destroy', 'ConversationsController@massDestroy')->name('conversations.massDestroy');
    Route::resource('conversations', 'ConversationsController');

    // Orders  
    Route::post('orders/send_to_wasla', 'OrdersController@send_to_wasla')->name('orders.send_to_wasla');
    Route::post('orders/update_delivery_man', 'OrdersController@update_delivery_man')->name('orders.update_delivery_man');
    Route::delete('orders/destroy_product/{id}', 'OrdersController@destroy_product')->name('orders.destroy_product');
    Route::get('orders/print/{id}', 'OrdersController@print')->name('orders.print');
    Route::post('orders/add_order_detail', 'OrdersController@add_order_detail')->name('orders.add_order_detail');
    Route::post('orders/store_order_detail', 'OrdersController@store_order_detail')->name('orders.store_order_detail');
    Route::post('orders/edit_order_detail', 'OrdersController@edit_order_detail')->name('orders.edit_order_detail');
    Route::post('orders/update_order_detail', 'OrdersController@update_order_detail')->name('orders.update_order_detail');
    Route::post('orders/show_order_detail', 'OrdersController@show_order_detail')->name('orders.show_order_detail');
    Route::post('orders/update_delivery_man', 'OrdersController@update_delivery_man')->name('orders.update_delivery_man');
    Route::post('orders/update_statuses', 'OrdersController@update_statuses')->name('orders.update_statuses');
    Route::post('orders/upload_fedex', 'OrdersController@upload_fedex')->name('orders.upload_fedex');
    Route::resource('orders', 'OrdersController');

    // Receipt Outgoing
    Route::delete('receipt-outgoings/destroy_product/{id}', 'ReceiptOutgoingController@destroy_product')->name('receipt-outgoings.destroy_product');
    Route::get('receipt-outgoings/restore/{id}', 'ReceiptOutgoingController@restore')->name('receipt-outgoings.restore');
    Route::get('receipt-outgoings/print/{id}', 'ReceiptOutgoingController@print')->name('receipt-outgoings.print');
    Route::get('receipt-outgoings/duplicate/{id}', 'ReceiptOutgoingController@duplicate')->name('receipt-outgoings.duplicate');
    Route::post('receipt-outgoings/update_statuses', 'ReceiptOutgoingController@update_statuses')->name('receipt-outgoings.update_statuses');
    Route::post('receipt-outgoings/view_products', 'ReceiptOutgoingController@view_products')->name('receipt-outgoings.view_products');
    Route::post('receipt-outgoings/add_product', 'ReceiptOutgoingController@add_product')->name('receipt-outgoings.add_product');
    Route::post('receipt-outgoings/edit_product', 'ReceiptOutgoingController@edit_product')->name('receipt-outgoings.edit_product');
    Route::delete('receipt-outgoings/destroy', 'ReceiptOutgoingController@massDestroy')->name('receipt-outgoings.massDestroy');
    Route::resource('receipt-outgoings', 'ReceiptOutgoingController');

    // Receipt Outgoing Product
    // Route::delete('receipt-outgoing-products/destroy', 'ReceiptOutgoingProductController@massDestroy')->name('receipt-outgoing-products.massDestroy');
    // Route::resource('receipt-outgoing-products', 'ReceiptOutgoingProductController');

    // Receipt Price View
    Route::delete('receipt-price-views/destroy_product/{id}', 'ReceiptPriceViewController@destroy_product')->name('receipt-price-views.destroy_product');
    Route::get('receipt-price-views/restore/{id}', 'ReceiptPriceViewController@restore')->name('receipt-price-views.restore');
    Route::get('receipt-price-views/print/{id}', 'ReceiptPriceViewController@print')->name('receipt-price-views.print');
    Route::get('receipt-price-views/duplicate/{id}', 'ReceiptPriceViewController@duplicate')->name('receipt-price-views.duplicate');
    Route::post('receipt-price-views/update_statuses', 'ReceiptPriceViewController@update_statuses')->name('receipt-price-views.update_statuses');
    Route::post('receipt-price-views/view_products', 'ReceiptPriceViewController@view_products')->name('receipt-price-views.view_products');
    Route::post('receipt-price-views/add_product', 'ReceiptPriceViewController@add_product')->name('receipt-price-views.add_product');
    Route::post('receipt-price-views/edit_product', 'ReceiptPriceViewController@edit_product')->name('receipt-price-views.edit_product');
    Route::delete('receipt-price-views/destroy', 'ReceiptPriceViewController@massDestroy')->name('receipt-price-views.massDestroy');
    Route::resource('receipt-price-views', 'ReceiptPriceViewController');

    // Receipt Price View Product
    // Route::delete('receipt-price-view-products/destroy', 'ReceiptPriceViewProductController@massDestroy')->name('receipt-price-view-products.massDestroy');
    // Route::resource('receipt-price-view-products', 'ReceiptPriceViewProductController');

    // Excel Files
    Route::delete('excel-files/destroy', 'ExcelFilesController@massDestroy')->name('excel-files.massDestroy');
    Route::post('excel-files/media', 'ExcelFilesController@storeMedia')->name('excel-files.storeMedia');
    Route::post('excel-files/ckmedia', 'ExcelFilesController@storeCKEditorImages')->name('excel-files.storeCKEditorImages');
    Route::resource('excel-files', 'ExcelFilesController');

    // Products
    Route::delete('products/destroy', 'ProductsController@massDestroy')->name('products.massDestroy');
    Route::post('products/edit_prices', 'ProductsController@edit_prices')->name('products.edit_prices');
    Route::post('products/sorting_images', 'ProductsController@sorting_images')->name('products.sorting_images');
    Route::post('products/update_product', 'ProductsController@update')->name('products.update_product');
    Route::post('products/update_statuses', 'ProductsController@update_statuses')->name('products.update_statuses');
    Route::post('products/sku_combination', 'ProductsController@sku_combination')->name('products.sku_combination');
    Route::post('products/sku_combination_edit', 'ProductsController@sku_combination_edit')->name('products.sku_combination_edit');
    Route::post('products/get_sub_categories_by_category', 'ProductsController@get_sub_categories_by_category')->name('products.get_sub_categories_by_category');
    Route::post('products/get_sub_sub_categories_by_subcategory', 'ProductsController@get_sub_sub_categories_by_subcategory')->name('products.get_sub_sub_categories_by_subcategory');
    Route::post('products/media', 'ProductsController@storeMedia')->name('products.storeMedia');
    Route::post('products/ckmedia', 'ProductsController@storeCKEditorImages')->name('products.storeCKEditorImages');
    Route::resource('products', 'ProductsController');

    // Sub Category
    Route::delete('sub-categories/destroy', 'SubCategoryController@massDestroy')->name('sub-categories.massDestroy');
    Route::post('sub-categories/update_statuses', 'SubCategoryController@update_statuses')->name('sub-categories.update_statuses');
    Route::resource('sub-categories', 'SubCategoryController');

    // Sub Sub Category
    Route::delete('sub-sub-categories/destroy', 'SubSubCategoryController@massDestroy')->name('sub-sub-categories.massDestroy');
    Route::post('sub-sub-categories/update_statuses', 'SubSubCategoryController@update_statuses')->name('sub-sub-categories.update_statuses');
    Route::resource('sub-sub-categories', 'SubSubCategoryController');

    // Attributes
    Route::delete('attributes/destroy', 'AttributesController@massDestroy')->name('attributes.massDestroy');
    Route::resource('attributes', 'AttributesController');

    // Reviews
    Route::post('reviews/update_statuses', 'ReviewsController@update_statuses')->name('reviews.update_statuses');
    Route::resource('reviews', 'ReviewsController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Playlist
    Route::delete('playlists/destroy', 'PlaylistController@massDestroy')->name('playlists.massDestroy');
    Route::get('playlists/client_review/{id}/{model_type}', 'PlaylistController@client_review')->name('playlists.client_review');
    Route::post('playlists/update_playlist_users', 'PlaylistController@update_playlist_users')->name('playlists.update_playlist_users');
    Route::post('playlists/playlist_users', 'PlaylistController@playlist_users')->name('playlists.playlist_users');
    Route::post('playlists/update_playlist_status', 'PlaylistController@update_playlist_status')->name('playlists.update_playlist_status');
    Route::post('playlists/check_printable', 'PlaylistController@check_printable')->name('playlists.check_printable');
    Route::post('playlists/show_details', 'PlaylistController@show_details')->name('playlists.show_details');
    Route::post('playlists/required_items', 'PlaylistController@required_items')->name('playlists.required_items');
    Route::get('playlists/print/{id}/{model_type}', 'PlaylistController@print')->name('playlists.print');
    Route::get('playlists/{type}', 'PlaylistController@index')->name('playlists.index');

    // Faq Category
    Route::delete('faq-categories/destroy', 'FaqCategoryController@massDestroy')->name('faq-categories.massDestroy');
    Route::resource('faq-categories', 'FaqCategoryController');

    // Faq Question
    Route::delete('faq-questions/destroy', 'FaqQuestionController@massDestroy')->name('faq-questions.massDestroy');
    Route::resource('faq-questions', 'FaqQuestionController');

    // Mockups
    Route::delete('mockups/destroy', 'MockupsController@massDestroy')->name('mockups.massDestroy');
    Route::post('mockups/update_mockup', 'MockupsController@update_mockup')->name('mockups.update_mockup');
    Route::post('mockups/sku_combination', 'MockupsController@sku_combination')->name('mockups.sku_combination');
    Route::post('mockups/sku_combination_edit', 'MockupsController@sku_combination_edit')->name('mockups.sku_combination_edit');
    Route::resource('mockups', 'MockupsController');

    // Designers
    Route::delete('designers/destroy', 'DesignersController@massDestroy')->name('designers.massDestroy');
    Route::post('designers/media', 'DesignersController@storeMedia')->name('designers.storeMedia');
    Route::post('designers/ckmedia', 'DesignersController@storeCKEditorImages')->name('designers.storeCKEditorImages');
    Route::resource('designers', 'DesignersController');

    // Designs
    Route::post('designs/design_images','DesignsController@design_images')->name('desgins.design_images');
    Route::get('designs/change_status/{id}/{status}','DesignsController@change_status')->name('designs.change_status');
    Route::delete('designs/destroy', 'DesignsController@massDestroy')->name('designs.massDestroy');
    Route::resource('designs', 'DesignsController');

    // Delivery Orders
    Route::delete('delivery-orders/destroy', 'DeliveryOrdersController@massDestroy')->name('delivery-orders.massDestroy');
    Route::resource('delivery-orders', 'DeliveryOrdersController');

    // Deliver Man
    Route::delete('deliver-men/destroy', 'DeliverManController@massDestroy')->name('deliver-men.massDestroy');
    Route::resource('deliver-men', 'DeliverManController');

    // Contactus
    Route::delete('contactus/destroy', 'ContactusController@massDestroy')->name('contactus.massDestroy');
    Route::resource('contactus', 'ContactusController', ['except' => ['create', 'store', 'edit', 'update']]);

    // Subscribe
    Route::delete('subscribes/destroy', 'SubscribeController@massDestroy')->name('subscribes.massDestroy');
    Route::resource('subscribes', 'SubscribeController', ['except' => ['create', 'store', 'edit', 'update', 'show']]);
    
    // Expense Category
    Route::delete('expense-categories/destroy', 'ExpenseCategoryController@massDestroy')->name('expense-categories.massDestroy');
    Route::resource('expense-categories', 'ExpenseCategoryController');

    // Income Category
    Route::delete('income-categories/destroy', 'IncomeCategoryController@massDestroy')->name('income-categories.massDestroy');
    Route::resource('income-categories', 'IncomeCategoryController');

    // Expense
    Route::delete('expenses/destroy', 'ExpenseController@massDestroy')->name('expenses.massDestroy');
    Route::resource('expenses', 'ExpenseController');

    // Income
    Route::delete('incomes/destroy', 'IncomeController@massDestroy')->name('incomes.massDestroy');
    Route::resource('incomes', 'IncomeController');

    // Expense Report
    Route::delete('expense-reports/destroy', 'ExpenseReportController@massDestroy')->name('expense-reports.massDestroy');
    Route::resource('expense-reports', 'ExpenseReportController');

    // R Clients
    Route::delete('r-clients/destroy', 'RClientsController@massDestroy')->name('r-clients.massDestroy');
    Route::resource('r-clients', 'RClientsController');

    // R Branche
    Route::delete('r-branches/destroy', 'RBrancheController@massDestroy')->name('r-branches.massDestroy');
    Route::resource('r-branches', 'RBrancheController');

    // Qr Products 
    Route::get('qr-products/destroy/{id}', 'QrProductController@destroy')->name('qr-products.destroy');
    Route::get('qr-products/destroy_product/{id}', 'QrProductController@destroy_product')->name('qr-products.destroy_product');
    Route::get('qr-products/print/{id}', 'QrProductController@print')->name('qr-products.print');
    Route::get('qr-products/delete_name/{name_id}/{qr_product_rbranch_id}', 'QrProductController@delete_name')->name('qr-products.delete_name');
    Route::post('qr-products/get_names', 'QrProductController@get_names')->name('qr-products.get_names');
    Route::post('qr-products/load_needs', 'QrProductController@load_needs')->name('qr-products.load_needs');
    Route::post('qr-products/printmore', 'QrProductController@printmore')->name('qr-products.printmore');
    Route::post('qr-products/save_print', 'QrProductController@save_print')->name('qr-products.save_print');
    Route::post('qr-products/view_result', 'QrProductController@view_result')->name('qr-products.view_result');
    Route::post('qr-products/view_scanner', 'QrProductController@view_scanner')->name('qr-products.view_scanner');
    Route::post('qr-products/qr_output', 'QrProductController@qr_output')->name('qr-products.qr_output');
    Route::post('qr-products/start_scan', 'QrProductController@start_scan')->name('qr-products.start_scan');
    Route::post('qr-products/show', 'QrProductController@show')->name('qr-products.show');
    Route::post('qr-products/update', 'QrProductController@update')->name('qr-products.update');
    Route::post('qr-products/update_product', 'QrProductController@update_product')->name('qr-products.update_product');
    Route::post('qr-products/store', 'QrProductController@store')->name('qr-products.store');
    Route::post('qr-product-rbranch/store', 'QrProductController@store2')->name('qr-product-rbranch.store');

    // Financial Accounts
    Route::delete('financial-accounts/destroy', 'FinancialAccountsController@massDestroy')->name('financial-accounts.massDestroy');
    Route::post('financial-accounts/update_statuses', 'FinancialAccountsController@update_statuses')->name('financial-accounts.update_statuses');
    Route::resource('financial-accounts', 'FinancialAccountsController');
    
    // Materials
    Route::delete('materials/destroy', 'MaterialsController@massDestroy')->name('materials.massDestroy');
    Route::post('materials/stock', 'MaterialsController@stock')->name('materials.stock');
    Route::resource('materials', 'MaterialsController');
    
    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Profile
    Route::post('wasla_login', 'ProfileController@wasla_login')->name('wasla_login');
    Route::get('wasla_logout','ProfileController@wasla_logout')->name('wasla_logout'); 
    Route::post('profile', 'ProfileController@updateProfile')->name('updateProfile');

    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
