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

Route::post('/login',"Api\AuthController@login");
Route::post('/register',"Api\AuthController@register");

Route::get('email/verify/{id}', 'Api\VerificationController@verify')->name('verification.verify');

Route::group(['middleware' => 'auth:api'], function(){
    
    
    Route::get('cart-cek/{idProduk}/{idUser}/{size}/{kategori}','Api\CartController@cartCek');
    
    Route::get('cart/{id}', 'Api\CartController@show');
    Route::post('cart', 'Api\CartController@store');
    Route::put('cart/{id}', 'Api\CartController@update');
    Route::delete('cart/{id}', 'Api\CartController@destroy');
    
    Route::put('cart-update/{idProduk}/{idUser}/{size}/{kategori}/{jumlah}','Api\CartController@update'); 

    
    Route::get('user/{id}','Api\AuthController@readData');
    Route::put('user/{id}','Api\AuthController@updateUser');
    Route::put('user-password/{id}','Api\AuthController@updatePasswordAndData');
    Route::post('user/upload-image/{id}','Api\AuthController@uploadImage');
});

Route::get('cart/{id}', 'Api\CartController@index');

Route::get('man', 'Api\ManController@index');
Route::get('man-random', 'Api\ManController@getRandom');
Route::get('man/{id}', 'Api\ManController@show');
Route::post('man', 'Api\ManController@store');
Route::put('man/{id}', 'Api\ManController@update');
Route::delete('man/{id}', 'Api\ManController@destroy');
Route::post('man/upload-image/{id}', 'Api\ManController@uploadImage');

Route::get('woman', 'Api\WomanController@index');
Route::get('woman-random', 'Api\WomanController@getRandom');
Route::get('woman/{id}', 'Api\WomanController@show');
Route::post('woman', 'Api\WomanController@store');
Route::put('woman/{id}', 'Api\WomanController@update');
Route::delete('woman/{id}', 'Api\WomanController@destroy');
Route::post('woman/upload-image/{id}', 'Api\WomanController@uploadImage');

Route::get('acc', 'Api\AccessoryController@index');
Route::get('acc-random', 'Api\AccessoryController@getRandom');
Route::get('acc/{id}', 'Api\AccessoryController@show');
Route::post('acc', 'Api\AccessoryController@store');
Route::put('acc/{id}', 'Api\AccessoryController@update');
Route::delete('acc/{id}', 'Api\AccessoryController@destroy');
Route::post('acc/upload-image/{id}', 'Api\AccessoryController@uploadImage');

Route::get('order', 'Api\OrderPaymentController@index');
Route::get('order/{id}', 'Api\OrderPaymentController@show');
Route::post('order', 'Api\OrderPaymentController@store');
Route::put('order/{id}', 'Api\OrderPaymentController@update');
Route::delete('order/{id}', 'Api\OrderPaymentController@destroy');
