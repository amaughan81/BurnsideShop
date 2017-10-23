<?php

Route::group(['middleware' => ['web']], function() {

    Route::get('/', 'bbec\shop\Http\Controllers\ShopController@home');
    Route::get('/browse', 'bbec\shop\Http\Controllers\ShopController@browse');
    Route::get('/browse/{slug}', 'bbec\shop\Http\Controllers\ShopController@browse');

    Route::resource('categories', 'bbec\shop\Http\Controllers\CategoriesController');
    Route::resource('products', 'bbec\shop\Http\Controllers\ProductsController');
    Route::get('products/{slug}', 'bbec\shop\Http\Controllers\ProductsController@show');
    Route::post('products/{slug}/photos', 'bbec\shop\Http\Controllers\PhotosController@store');
    Route::delete('photos/{id}', 'bbec\shop\Http\Controllers\PhotosController@destroy');
    Route::get('cart/checkout', 'bbec\shop\Http\Controllers\CartController@checkout');
    Route::get('cart/checkout/{id}', 'bbec\shop\Http\Controllers\CartController@checkout');
    Route::delete('cart/empty', 'bbec\shop\Http\Controllers\CartController@empty');
    Route::post('cart/apply-coupon', 'bbec\shop\Http\Controllers\CartController@applyCoupon');
    Route::delete('cart/remove-coupon', 'bbec\shop\Http\Controllers\CartController@removeCoupon');
    Route::resource('cart', 'bbec\shop\Http\Controllers\CartController');

    Route::resource('orders', 'bbec\shop\Http\Controllers\OrdersController');
    Route::get('orders/status/{status}', ['as' => 'orders.index', 'uses' => 'bbec\shop\Http\Controllers\OrdersController@index']);
    Route::get('orders/complete/{id}', 'bbec\shop\Http\Controllers\OrdersController@complete');

    Route::resource('coupons', 'bbec\shop\Http\Controllers\CouponsController');
    Route::get('coupons/print/pdf', 'bbec\shop\Http\Controllers\CouponsController@pdf');

    Route::post('merits', 'bbec\shop\Http\Controllers\MeritsController@store');

    Route::get('my-account', 'bbec\shop\Http\Controllers\UsersController@account');
    Route::resource('users', 'bbec\shop\Http\Controllers\UsersController');

});