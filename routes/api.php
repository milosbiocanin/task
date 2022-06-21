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

Route::middleware('auth:api')->prefix('admin')->group(function () {
	Route::get('/userlist', 'App\Http\Controllers\CustomersController@getUsers');
	
  Route::post('/product/create',    'App\Http\Controllers\ProductController@saveProduct');
  Route::delete('/product/remove/{id}',    'App\Http\Controllers\ProductController@removeProduct');
	Route::get('/product/list',    'App\Http\Controllers\ProductController@getProducts');
	Route::get('/categories/list',    'App\Http\Controllers\CategoryController@getCategories');
  Route::post('/categories/create',    'App\Http\Controllers\CategoryController@saveCategory');
  Route::delete('/categories/remove/{id}',    'App\Http\Controllers\CategoryController@removeCategory');
	Route::get('/email/verify', 'App\Http\Controllers\UserController@sendVerifyEmail')->middleware('auth')->name('verification.notice');
});
Route::post('/email/verification-notification', ['uses' => 'App\Http\Controllers\Auth\VerificationController@sendVerifyEmail'])->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');