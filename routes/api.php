<?php

use Illuminate\Http\Request;

// Login, Register and Logout
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');

// User
Route::get('emails','Api\UserController@list_all_emails');
Route::get('users','Api\UserController@list_all_users');
Route::post('user/username','Api\UserController@check_username');
Route::post('user/email','Api\UserController@check_email');
Route::post('user/reset', 'Api\UserController@reset_password');

Route::put('user/update/{username}', 'Api\UserController@update_info_user');
Route::post('upload_photo', 'FileController@upload_photo');
Route::post('user/{username}/set_configurations', 'Api\UserController@set_configurations');
Route::get('user/{username}', 'Api\UserController@get_info_user');
Route::put('user/{username}/update', 'Api\UserController@update_info_user');

// Publication
Route::resource('publication', 'Api\PublicationController');
