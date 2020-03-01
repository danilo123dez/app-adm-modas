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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'customers', 'middleware' => 'client:customer-register'], function () {
    Route::post('/', 'CustomerController@store');

    Route::group(['prefix' => '{uuid}'], function () {
        Route::put('/', 'CustomerController@update');
        Route::delete('/', 'CustomerController@delete');
    });
});
