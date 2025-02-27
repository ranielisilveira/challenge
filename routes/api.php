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

Route::group(['prefix' => 'users'], function ($router) {
    Route::get('/', 'UserController@index');
    Route::post('/', 'UserController@store');
});

Route::group(['prefix' => 'accounts'], function ($router) {
    Route::post('/', 'AccountController@store');
    Route::get('/{id}', 'AccountController@show');
});

Route::post('/transactions', 'TransactionController@store');
