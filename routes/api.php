<?php

use Illuminate\Http\Request;

// Login, Register and Logout
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');

// User
Route::post('user/username','Api\UserController@check_username');
Route::post('user/email','Api\UserController@check_email');
Route::post('user/reset', 'Api\UserController@reset_password');

Route::post('upload_photo', 'FileController@upload_photo');
Route::get('user/{username}', 'Api\UserController@get_info_user');