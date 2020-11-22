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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('cart', 'Api\CartController@index');
    Route::get('cart/{id}', 'Api\CartController@show');
    Route::post('cart', 'Api\CartController@store');
    Route::put('cart/{id}', 'Api\CartController@update');
    Route::delete('cart/{id}', 'Api\CartController@destroy');
    Route::get('order', 'Api\OrderPaymentController@index');
    Route::get('order/{id}', 'Api\OrderPaymentController@show');
    Route::post('order', 'Api\OrderPaymentController@store');
    Route::put('order/{id}', 'Api\OrderPaymentController@update');
    Route::delete('order/{id}', 'Api\OrderPaymentController@destroy');
    Route::get('man', 'Api\ManController@index');
    Route::get('man/{id}', 'Api\ManController@show');
    Route::post('man', 'Api\ManController@store');
    Route::put('man/{id}', 'Api\ManController@update');
    Route::delete('man/{id}', 'Api\ManController@destroy');
});
