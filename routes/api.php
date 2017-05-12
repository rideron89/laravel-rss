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

Route::group(['prefix' => 'user-feeds'], function() {
    Route::get    ('/',           'UserFeedsController@index');
    Route::post   ('/',           'UserFeedsController@store');
    Route::delete ('/{userFeed}', 'UserFeedsController@destroy');
    Route::put    ('/{userFeed}', 'UserFeedsController@update');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'feed'], function() {
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


Route::group(['prefix' => 'posts'], function() {
    Route::get  ('/',            'PostsController@index');
    Route::post ('/{post}/read', 'PostsController@markRead');
    Route::post ('/read/',       'PostsController@bulkMarkRead');
});
