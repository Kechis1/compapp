<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'ShopController@index')->name('dashboard');
Route::get('/logout', 'ShopController@logout');
Route::get('/set/locale/{locale}', 'ShopController@setLocale');
Route::get('/profile', 'ShopController@profile')->name('profile');
Route::put('/profile/update', 'ShopController@profileUpdate');
Route::get('/manufacturers', 'ShopController@manufacturers')->name('manufacturers');
Route::get('/categories', 'ShopController@categories')->name('categories');
Route::get('/deliveries', 'ShopController@deliveries')->name('deliveries');
Route::get('/parameters', 'ShopController@parameters')->name('parameters');
Route::get('/products', 'ShopController@products')->name('products');
Route::get('/feed', 'ShopController@feed')->name('feed');

Route::get('/auth/sign-in', 'Auth\LoginController@showShopLoginForm');
Route::post('/auth/sign-in', 'Auth\LoginController@shopLogin');
