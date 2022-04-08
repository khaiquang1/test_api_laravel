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


Route::match(['get', 'post'],'register', 'App\Http\Controllers\Auth\RegisterController@Register')->name('user.register');
Route::match(['get', 'post'],'login', 'App\Http\Controllers\Auth\RegisterController@Login')->name('user.login');
//
Route::get('user/activation/{token}', 'App\Http\Controllers\Auth\RegisterController@activateUser')->name('user.activate');

 //Kích hoạt, gửi OTP
Route::match(['get', 'post'],'email/verify', 'App\Http\Controllers\Auth\RegisterController@verifyEmail')->name('email.verify');
    Route::get('email/re-verify-otp', 'App\Http\Controllers\Auth\RegisterController@reVerify')->name('email.verify_otp');

Route::middleware([checkAdminLogin::class])->group(function () {

    Route::get('logout', 'App\Http\Controllers\Auth\RegisterController@Logout')->name('user.logout');

    //user
    Route::get('user','App\Http\Controllers\Auth\UserController@index')->name('user');
    Route::post('reset-password','App\Http\Controllers\Auth\UserController@resetPassword')->name('user.reset_password');
    Route::post('user-verification','App\Http\Controllers\Auth\UserController@verificationUser');
    Route::post('user-verification/info','App\Http\Controllers\Auth\UserController@informationUser');

    //chặn user
    Route::get('user/block/{id}','App\Http\Controllers\Auth\UserController@blockUser');

    //thay đổi level user
    Route::get('user/level/{id}/{value}','App\Http\Controllers\Auth\UserController@changeLevelUser');

    //show users
    Route::get('user/list','App\Http\Controllers\Auth\UserController@showAll')->name('user.show_all');

    //authenticator
    Route::get('user/authenticator','App\Http\Controllers\Auth\AuthenticatorController@authenUser')->name('user.authenticator');
    Route::post('user/authen/enable','App\Http\Controllers\Auth\AuthenticatorController@enableAuthen')->name('authenticator.code');
    Route::match(['get', 'post'],'user/authen/disable','App\Http\Controllers\Auth\AuthenticatorController@disableAuthen')->name('authenticator.disable');

    //wallet 
    Route::get('user/wallet','App\Http\Controllers\Money\MoneyController@walletUser');

    //Nạp tiền
    Route::post('user/wallet/deposit','App\Http\Controllers\Money\MoneyController@depositMoney')->name('money.deposit');
    //Rút
    Route::post('user/wallet/withdraw','App\Http\Controllers\Money\MoneyController@withdrawMoney')->name('money.withdraw');
    //Chuyển 
    Route::post('user/wallet/transfer','App\Http\Controllers\Money\MoneyController@transferMoney')->name('money.transfer');

    //Đầu tư
    Route::match(['get', 'post'],'user/investment','App\Http\Controllers\Invest\InvestController@investment')->name('money.invest');

    //blog
    Route::resource('blog','App\Http\Controllers\Test\BlogController')->except('create','edit');
    
});