<?php

use Illuminate\Http\Request;

// Login and Register
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

// User
Route::post('user/username','Api\UserController@check_username');
