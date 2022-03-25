<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test;
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
Route::resource('blog','App\Http\Controllers\Test\BlogController')->except('create','edit');


Route::match(['get', 'post'],'register', 'App\Http\Controllers\Auth\RegisterController@register')->name('user.register');
Route::get('login', 'App\Http\Controllers\Auth\RegisterController@login')->name('user.login');
