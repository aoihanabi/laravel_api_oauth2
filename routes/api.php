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
    
    Route::get('getUsers', 'Api\UserdataController@getUsers');
    Route::get('getUsers/{id}', 'Api\UserdataController@getUserDetail');
    Route::post('getUsers', 'Api\UserdataController@addUsers');
    Route::put('getUsers', 'Api\UserdataController@updateUsers');
    Route::delete('getUsers', 'Api\UserdataController@deleteUsers');
});
//Out of the middleware just while developing
Route::get('actividad', 'Api\ActividadesController@getActividades');
Route::get('actividad/{id}', 'Api\ActividadesController@getActividadDetail');
Route::post('actividad', 'Api\ActividadesController@addActividad');
Route::put('actividad', 'Api\ActividadesController@updateActividad');
Route::delete('actividad', 'Api\ActividadesController@deleteActividad');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
