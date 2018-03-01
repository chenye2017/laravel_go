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

Route::get('/signup', 'UsersController@create')->name('signup');

Route::resource('users', 'UsersController');

Route::post('login', 'SessionsController@store')->name('login');
Route::get('login', 'SessionsController@create')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');
