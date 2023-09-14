<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['prefix' => 'v1'], function () {

    // Auth Routes
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login');

        Route::post('forget-password', 'AuthController@forget');
        Route::post('reset-password', 'AuthController@reset');
    });
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth','middleware' => 'auth.api'], function () {

        Route::get('info', 'AuthController@info');
        Route::post('logout', 'AuthController@logout');

    });

    Route::get('users/export/download', 'Users\UserController@downloadExport')
        ->name('users.export.download');

    // Guarded Routes
    Route::group(['middleware' => 'auth.api'], function () {



        // User Profile Routes
        Route::get('profile', 'Profile\ProfileController@index');
        Route::put('profile', 'Profile\ProfileController@update');

        // Users Crud Routes
        Route::post('users/export', 'Users\UserController@export');
        Route::delete('users', 'Users\UserController@bulkDestroy');
        Route::get('users/brief', 'Users\UserController@brief');
        Route::resource('users', 'Users\UserController')->except(['create', 'edit']);

        // Roles Crud Routes
        Route::get('roles/brief', 'Roles\RoleController@brief');
        Route::delete('roles', 'Roles\RoleController@bulkDestroy');
        Route::resource('roles', 'Roles\RoleController')->except(['create', 'edit']);

        // Permission Routes
        Route::get('permissions', 'Roles\PermissionController@index');
    });

    Route::get('about', fn () => response()->json([
        "success" => true,
        "code" => 200,
        "message" => "🥳 bevatel api version 1 🥳"
    ]));
});
