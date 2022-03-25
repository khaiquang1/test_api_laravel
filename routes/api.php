<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Blog;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('blogs', function(){
//     return Blog::all();
// });

// Route::get('blog/{id}',function($id){
//     return Blog::find($id);
// });

// Route::post('blog', function(Request $request){
//     return Blog::create($request->all);
// });

// Route::put('blog/{id}', function(Request $request, $id){
//     $blog = Blog::findOrFail($id);
//     $blog -> update($request->all());

//     return $blog;
// });

// Route::delete('', function($id){
//     Blog::find($id)->delete();
//     return 204;
// });


Route::post('login', 'App\Http\Controllers\Auth\UserController@login');
Route::post('register', 'App\Http\Controllers\Auth\UserController@register');
Route::group(['middleware' => 'auth:api'], function() {
Route::post('details', 'App\Http\Controllers\Auth\UserController@details');
});

//exam API
Route::namespace('App\Http\Controllers\Api')->prefix('v1')->group(function(){
    Route::resource('exams','ExamController')
        ->except('create');
});

