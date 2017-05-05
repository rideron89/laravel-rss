<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'feed'], function() {
    Route::get(   '/',       'FeedsController@index');
    Route::post(  '/',       'FeedsController@store');
    Route::delete('/{feed}', 'FeedsController@destroy');
    Route::put(   '/{feed}', 'FeedsController@update');
});

Route::group(['prefix' => 'user-feed'], function() {
    Route::post('/', 'UserFeedsController@store');
    Route::delete('/{user_feed}', 'UserFeedsController@destroy');
    Route::put('/{user_feed}', 'UserFeedsController@update');
});

Route::group(['prefix' => 'post'], function() {
    Route::post('/load', 'PostsController@load');
    Route::post('/{post}/read', 'PostsController@markRead');
});
