<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test;
use App\Http\Middleware\checkAdminLogin;
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

// Route::prefix('blog')->group(function () {
//     Route::get('data', 'App\Http\Controllers\Test\BlogController@data')->name('blog.data');
// });



Route::match(['get', 'post'],'register', 'App\Http\Controllers\Auth\RegisterController@Register')->name('user.register');
Route::match(['get', 'post'],'login', 'App\Http\Controllers\Auth\RegisterController@Login')->name('user.login');
Route::get('logout', 'App\Http\Controllers\Auth\RegisterController@Logout')->name('user.logout');

Route::middleware([checkAdminLogin::class])->group(function () {
    Route::resource('blog','App\Http\Controllers\Test\BlogController')->except('create','edit');
});