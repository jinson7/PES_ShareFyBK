<?php

use Illuminate\Http\Request;

// Login and Register
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');

// User
Route::post('user/username','Api\UserController@check_username');
Route::post('user/email','Api\UserController@check_email');
