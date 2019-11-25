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

Route::post('user/{username}/photo', 'FileController@upload_photo');
Route::post('user/{username}/configuration', 'Api\UserController@set_configurations');
Route::get('user/{username}', 'Api\UserController@get_info_user');
Route::put('user/{username}', 'Api\UserController@update_info_user');
Route::post('user/{username}/token_password', 'Api\UserController@set_token');
Route::post('user/{username}/token_notification', 'Api\UserController@set_token_notification');

// Publication
Route::resource('publication', 'Api\PublicationController');
Route::get('user/{id_user}/publications','Api\PublicationController@list_publication_user');

// Games
Route::get('games','Api\GameController@index');

// Like
Route::get('like/user/{username}/publication/{id_publication}', 'Api\LikeController@is_like');
Route::post('like/user/{username}/publication/{id_publication}', 'Api\LikeController@set_like');
Route::delete('like/user/{username}/publication/{id_publication}', 'Api\LikeController@unset_like');

// Comment
Route::post('comment/user/{username}/publication/{id_publication}', 'Api\CommentController@create');
Route::get('comment/{id}', 'Api\CommentController@get');
Route::delete('comment/{id}', 'Api\CommentController@delete');

// Follower
Route::post('follow/user/{username}', 'Api\FollowerController@create');
Route::delete('follow/user/{follower}/user/{followed}', 'Api\FollowerController@delete');
Route::get('follow/user/{follower}/user/{followed}', 'Api\FollowerController@is_following');

//Notification
Route::get('notification/create', 'Api\NotificationController@testFirebase');
Route::get('notification/like/', 'Api\NotificationController@sendNotification');
Route::get('notification/comment/', 'Api\NotificationController@sendNotification');
Route::get('notification/invite/', 'Api\NotificationController@sendNotification');
Route::get('notification/follow/', 'Api\NotificationController@sendNotification');
Route::get('notification/share/', 'Api\NotificationController@sendNotification');