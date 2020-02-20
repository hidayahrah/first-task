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
Route::get('/', function () {
    return view('welcome');
});

Route::get('login', 'API\UserController@showLogin')->name('login');
Route::post('login', 'API\UserController@login')->name('login');
Route::get('create', 'API\UserController@showCreate')->name('create');
Route::post('create', 'API\UserController@create')->name('create');
Route::get('logout', 'API\UserController@logout')->name('logout');
//Auth::routes();

Route::group(['middleware' => 'auth'], function(){
Route::get('/home', 'API\UserController@index')->name('home');

Route::get('/vimigo/register', 'API\UserController@register')->name('register');

Route::post('/vimigo/register', 'API\UserController@store')->name('store');

Route::post('/vimigo/{user}', 'API\UserController@update')->name('update');

Route::get('/vimigo/delete/{user}', 'API\UserController@delete')->name('delete');

Route::post('/vimigo/delete/{user}', 'API\UserController@destroy')->name('destroy');

Route::get('/vimigo/{user}', 'API\UserController@show')->name('show');
});
