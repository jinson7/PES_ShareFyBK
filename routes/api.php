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

Route::get('user/{username}', 'Api\UserController@get_info_user');
Route::put('user/update/{username}', 'Api\UserController@update_info_user');
/*
Route::put('user/update/{username}', function(Request $request, $username){
    return App::make('App\Http\Controllers\Api\UserController')->update_info_user($request, $username);
})->middleware('jwt');
*/