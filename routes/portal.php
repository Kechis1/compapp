<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'PagesController@home');
Route::get('/set/locale/{locale}', function ($locale) {
    App::setLocale($locale);
});

Route::post('/auth/sign-up', 'AuthController@signUp');
Route::patch('/auth/forgot-password', 'AuthController@forgotPassword');
Route::patch('/auth/refresh-password/{actId}/{amrCodeRefresh}', 'AuthController@refreshPassword');

Route::get('/category/{cleUrl?}', 'PagesController@category')
    ->where('cleUrl', '(.*)');

Route::get('/companies', 'PagesController@companies');
Route::get('/companies/{actId}', 'PagesController@company');
Route::post('/companies/{company}', 'ReviewController@storeCompany');

Route::get('/offers/{product}', 'PagesController@offers');
Route::post('/offers/{product}', 'ReviewController@storeProduct');

Route::get('/search', 'PagesController@search');

Route::get('/auth/sign-up', 'PagesController@shopSignUp');
Route::get('/auth/forgot-password', 'PagesController@shopForgotPassword');
Route::get('/auth/refresh-password/{actId}/{amrCodeRefresh}', 'PagesController@refreshPassword');