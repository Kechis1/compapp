<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'AdminController@index')->name('dashboard');
Route::get('/logout', 'AdminController@logout');
Route::get('/set/locale/{locale}', 'AdminController@setLocale');
Route::resource('profile', 'ProfileController')->middleware('auth:admin');
Route::resource('parameters', 'ParameterController')->middleware('auth:admin');
Route::resource('categories', 'CategoryController')->middleware('auth:admin');
Route::resource('companies', 'CompanyController')->middleware('auth:admin');
Route::resource('languages', 'LanguageController')->middleware('auth:admin');
Route::resource('guides', 'GuideController')->middleware('auth:admin');
Route::resource('manufacturers', 'ManufacturerController')->middleware('auth:admin');
Route::resource('reviews', 'ReviewController')->middleware('auth:admin');
Route::resource('products', 'ProductController')->middleware('auth:admin');
Route::resource('deliveries', 'DeliveryController')->middleware('auth:admin');
Route::resource('images', 'ImageController')->middleware('auth:admin');
Route::patch('/companies/{account}/status-update', 'CompanyController@statusUpdate')->middleware('auth:admin');
Route::delete('/companies/{account}/review', 'CompanyController@destroyReview')->middleware('auth:admin');
Route::patch('/companies/{account}/product-update', 'CompanyController@productUpdate')->middleware('auth:admin');
Route::patch('/products/{product}/status-update', 'ProductController@statusUpdate')->middleware('auth:admin');
Route::get('/categories/{ceyId}/parameters', 'ParameterController@getParamsByCeyId')->middleware('auth:admin');
Route::get('/parameters/{prrId}/choices', 'ParameterController@getChoicesByPrrId')->middleware('auth:admin');

Route::get('/auth/sign-in', 'Auth\LoginController@showAdminLoginForm');
Route::post('/auth/sign-in', 'Auth\LoginController@adminLogin');