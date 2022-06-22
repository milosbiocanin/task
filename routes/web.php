<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Auth::routes(['verify' => true]);


Route::get('auth/google', 'App\Http\Controllers\Auth\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'App\Http\Controllers\Auth\GoogleController@handleGoogleCallback');
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@authenticate')->name('login');
Route::post('/password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@resetPassword')->middleware('guest')->name('password.update');
Route::get('/customer/invoice/{id}', 'App\Http\Controllers\InvoiceController@customerview');

Route::group(['middleware' => 'auth'], function () {
  Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('profile/business', ['as' => 'business.edit', 'uses' => 'App\Http\Controllers\BusinessController@edit']);
	Route::put('profile/business', ['as' => 'business.update', 'uses' => 'App\Http\Controllers\BusinessController@update']);
	Route::post('profile/business/updateLogo', ['as' => 'business.updateLogo', 'uses' => 'App\Http\Controllers\BusinessController@updateLogo']);
	Route::get('products', function () {
    if (auth()->user()->level == 2) {
      return redirect('/home');
    }
		$token = Str::random(60);
		auth()->user()->forceFill([
			'api_token' => hash('sha256', $token),
		])->save();
		return view('pages.products');}
	)->name('products');
  Route::get('categories', function () {
    if (auth()->user()->level == 2) {
      return redirect('/home');
    }
		$token = Str::random(60);
		auth()->user()->forceFill([
			'api_token' => hash('sha256', $token),
		])->save();
		return view('pages.categories');}
	)->name('categories');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	
	Route::get('setting/members', ['uses' => 'App\Http\Controllers\TeamController@index'])->name('team.index');
	Route::post('setting/members/create', ['uses' => 'App\Http\Controllers\TeamController@create']);
});

Route::get('/email/verify/{id}/{hash}', ['uses' => 'App\Http\Controllers\Auth\VerificationController@verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');