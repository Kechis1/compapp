<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/category/params/{cleUrl?}', 'ProductParametersController@getProductParametersByCleUrl')
    ->where('cleUrl', '(.*)');

Route::get('/search/params/', 'ProductParametersController@getProductParametersBySearch');

Route::get('/guide/page/{page?}', 'GuideController@getGuideByPage')
    ->where('page', '(.*)');

Route::get('/guide/choice/{choice}', 'GuideController@getStepByChoice');
Route::get('/guide/id/{guide}', 'GuideController@getStepByGuide');
Route::get('/guide/choices/{prrId}/{min}/{max}', 'ParameterValueLanguageController@getValuesByPrrIdAndMinMax');