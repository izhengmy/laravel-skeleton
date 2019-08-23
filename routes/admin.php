<?php

Route::prefix('/app')->group(function () {
    Route::get('/menus', 'AppController@menus');
});

Route::prefix('/auth')->group(function () {
    Route::get('/captcha', 'AuthController@captcha');
    Route::post('/password-login', 'AuthController@passwordLogin');
    Route::get('/sms-captcha', 'AuthController@smsCaptcha');
    Route::post('/sms-captcha-login', 'AuthController@smsCaptchaLogin');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/reset-password', 'AuthController@resetPassword');
});

Route::prefix('/permissions')->group(function () {
    Route::get('/', 'PermissionsController@index');
    Route::post('/', 'PermissionsController@store');
    Route::get('/{id}', 'PermissionsController@show');
    Route::put('/{id}', 'PermissionsController@update');
    Route::delete('/{id}', 'PermissionsController@destroy');
});

Route::prefix('/roles')->group(function () {
    Route::get('/', 'RolesController@index');
    Route::post('/', 'RolesController@store');
    Route::get('/{id}', 'RolesController@show');
    Route::put('/{id}', 'RolesController@update');
    Route::delete('/{id}', 'RolesController@destroy');
});

Route::prefix('/admins')->group(function () {
    Route::get('/', 'AdminsController@index');
    Route::post('/', 'AdminsController@store');
    Route::get('/{id}', 'AdminsController@show');
    Route::put('/{id}', 'AdminsController@update');
    Route::delete('/{id}', 'AdminsController@destroy');
    Route::post('/{id}/restore', 'AdminsController@restore');
});

Route::prefix('/menus')->group(function () {
    Route::get('/', 'MenusController@index');
    Route::post('/', 'MenusController@store');
    Route::get('/{id}', 'MenusController@show');
    Route::put('/{id}', 'MenusController@update');
    Route::delete('/{id}', 'MenusController@destroy');
});

Route::prefix('/account')->group(function () {
    Route::get('/profile', 'AccountController@profile');
    Route::put('/profile', 'AccountController@updateProfile');
    Route::put('/password', 'AccountController@updatePassword');
});

Route::get('/easy-sms-logs', 'EasySmsLogsController@index');
