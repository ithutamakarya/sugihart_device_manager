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



//Page
Route::get('/admin/user_list', 'AdminPageController@users');
Route::get('/admin/group_media_list', 'AdminPageController@group_media');
Route::get('/admin/sub_group_media_list', 'AdminPageController@sub_group_media');
Route::get('/admin/media_list', 'AdminPageController@media');
Route::get('/admin/category_list', 'AdminPageController@category');
Route::get('/admin/sub_category_list', 'AdminPageController@sub_category');
Route::get('/admin/competitor_list', 'AdminPageController@competitor');
Route::get('/admin/keyword_list', 'AdminPageController@keywords');
Route::get('/admin/log_activity', 'AdminPageController@log_activity');


//Action Admin ==> Users 
Route::post('/admin/create_user', 'AdminActionController@create_user');
Route::post('/admin/update_user', 'AdminActionController@update_user');
Route::get('/admin/delete_user/{id}', 'AdminActionController@delete_user');

//Action Admin ==> Group Media 
Route::post('/admin/create_group_media', 'AdminActionController@create_group_media');
Route::post('/admin/update_group_media', 'AdminActionController@update_group_media');
Route::get('/admin/delete_group_media/{id}', 'AdminActionController@delete_group_media');

//Action Admin ==> Sub Group Media
Route::post('/admin/create_sub_group_media', 'AdminActionController@create_sub_group_media');
Route::post('/admin/update_sub_group_media', 'AdminActionController@update_sub_group_media');
Route::get('/admin/delete_sub_group_media/{id}', 'AdminActionController@delete_sub_group_media');

//Action Admin ==> Media
Route::post('/admin/create_media', 'AdminActionController@create_media');
Route::post('/admin/update_media', 'AdminActionController@update_media');
Route::get('/admin/delete_media/{id}', 'AdminActionController@delete_media');

//Action Admin ==> Category
Route::post('/admin/create_category', 'AdminActionController@create_category');
Route::post('/admin/update_category', 'AdminActionController@update_category');
Route::get('/admin/delete_category/{id}', 'AdminActionController@delete_category');

//Action Admin ==> sub_Category
Route::post('/admin/create_sub_category', 'AdminActionController@create_sub_category');
Route::post('/admin/update_sub_category', 'AdminActionController@update_sub_category');
Route::get('/admin/delete_sub_category/{id}', 'AdminActionController@delete_sub_category');


//Action Admin ==> Competitor
Route::post('/admin/create_competitor', 'AdminActionController@create_competitor');
Route::post('/admin/update_competitor', 'AdminActionController@update_competitor');
Route::get('/admin/delete_competitor/{id}', 'AdminActionController@delete_competitor');

//Action Admin ==> Keywords
Route::post('/admin/create_keywords', 'AdminActionController@create_keywords');
Route::post('/admin/update_keywords', 'AdminActionController@update_keywords');
Route::get('/admin/delete_keywords/{id}', 'AdminActionController@delete_keywords');

