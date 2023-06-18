<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {
    Route::prefix('dashboard')->middleware('auth')->name('dashboard.')->group(function () {

        //Dashboard routes
        Route::resource('', 'DashboardController');
        Route::get('/403', 'DashboardController@error403');

        //users routes
        Route::resource('users', 'UserController');

        //categories routes
        Route::resource('categories', 'CategoryController');

        //products routes
        Route::resource('products', 'ProductController');

        //clients routes
        Route::resource('clients', 'ClientController');
        Route::resource('clients.orders', 'Client\OrderController');
        Route::get('/clients/{client}/orders/{order}/edit', 'Client\OrderController@edit')->name('clients.orders.edit');
        Route::post('/clients/{client}/orders/{order}/update', 'Client\OrderController@update')->name('clients.orders.update');

        //orders routes
        Route::resource('orders', 'OrderController');
        Route::get('/orders/{order}/products', 'OrderController@products')->name('orders.products');

    });//end of dashboard routes

});
