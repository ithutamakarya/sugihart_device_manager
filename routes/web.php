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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/logout','UserController@logout');

//User
Route::get('/user_list', 'UserController@index');
Route::post('/create_user', 'UserController@create');
Route::post('/update_user', 'UserController@update');
Route::get('/delete_user/{id}', 'UserController@delete');

