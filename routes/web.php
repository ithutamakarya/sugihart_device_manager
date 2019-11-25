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

Route::get('/', 'MainController@index')->name('home');

Route::get('/logout','UserController@logout');



//Page
Route::get('/main/user_list', 'MainController@users');
Route::get('/main/device/{vendor_id}', 'MainController@device');
Route::get('/main/log_activity', 'MainController@log_activity');

//Action Admin ==> Users 
Route::post('/main/create_user', 'MainActionController@user_create');
Route::post('/main/update_user', 'MainActionController@user_update');
Route::get('/main/delete_user/{id}/{vendor_id}', 'MainActionController@user_delete');

//Action Admin ==> devices 
Route::post('/main/create_device', 'MainActionController@device_create');
Route::post('/main/update_device', 'MainActionController@device_update');
Route::get('/main/delete_device/{id}', 'MainActionController@device_delete');


Route::get('/api/get_data_cctv', 'ApiController@getDataCCTV');
Route::get('/api/get_kios', 'ApiController@getKios');
Route::get('/api/get_device/{token}', 'ApiController@getDevice');


