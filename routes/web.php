<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'StaticPagesController@home');
Route::get('/about', 'StaticPagesController@about')->name('about');
Route::get('/help', 'StaticPagesController@help')->name('help');

//用户注册页面
Route::get('/signup', 'UsersController@create')->name('signup');

Route::delete('/users/{user}', 'UsersController@destory')->name('users.destroy');

Route::resource('users', 'UsersController');

Route::post('login', 'SessionsController@store')->name('login');
Route::get('login', 'SessionsController@create')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');

//验证邮箱
Route::get('/users/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

//重置密码逻辑
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');


Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

//重置密码页面
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

//微博的发布
Route::post('/statuses', 'StatusesController@store')->name('statuses.store');

//微博的删除
Route::delete('/statuses/{status}', 'StatusesController@destroy')->name('statuses.destroy');