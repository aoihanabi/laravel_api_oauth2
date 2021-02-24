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
Route::post('register', 'Api\AuthController@register');

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('testOauth', 'Api\AuthController@testOauth');
    
});
//Out of the middleware just while developing
Route::get('getUsers', 'Api\UserdataController@getUsers');
Route::get('getUsers/{id}', 'Api\UserdataController@getUserDetail');
Route::post('getUsers', 'Api\UserdataController@addUsers');
Route::put('getUsers', 'Api\UserdataController@updateUsers');
Route::delete('getUsers', 'Api\UserdataController@deleteUsers');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
