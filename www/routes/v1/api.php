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

Route::get('/', function () {
	return response()->json([
		'error' => 1,
		'code' => 'oauth_unauthorized',
		'description' => "Winning is not everything, but the effort to win is. - Zig Ziglar"
	], 401);
})->name('login');

Route::post('login', 'LoginController@login');

Route::middleware('auth:api')->get('/user', 'CustomerController@infoUserCustomer');

Route::group(['prefix' => 'customers', 'middleware' => 'client:customer'], function () {
    Route::post('/', 'CustomerController@store');

    Route::group(['prefix' => '{uuid}'], function () {
        Route::put('/', 'CustomerController@update');
        Route::delete('/', 'CustomerController@delete');
    });
});

Route::group(['prefix' => 'lojas', 'middleware' => 'client:store'], function () {
    Route::group(['prefix' => '{customer_uuid}'], function () { 
        Route::get('/', 'LojasController@index');
        Route::post('/', 'LojasController@store');

        Route::group(['prefix' => '{store_uuid}'], function () {
            Route::get('/', 'LojasController@show');
            Route::put('/', 'LojasController@update');
            Route::delete('/', 'LojasController@delete');
        });
    });
});

Route::group(['prefix' => 'lancamentos', 'middleware' => 'client:release'], function () {
    Route::group(['prefix' => '{customer_uuid}'], function () { 
        Route::get('/', 'LancamentosController@index');

        Route::group(['prefix' => '{store_uuid}'], function () { 
            Route::post('/', 'LancamentosController@store');

            Route::group(['prefix' => '{releases_uuid}'], function () {
                Route::get('/', 'LancamentosController@show');
                Route::put('/', 'LancamentosController@update');
                Route::delete('/', 'LancamentosController@delete');
            });
        });
    });
});