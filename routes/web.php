<?php

use Illuminate\Support\Facades\Route;

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


/** ui password reset page route to use in reset password email template */

Route::get('/', function(){
    return "Hello 😀😀";
});

Route::get('reset', function () {

    // adapt reset flow for mr frontender
    $token = request()->query('token');
    $email = request()->query('email');
    return redirect()->away(env('FRONT_URL') . "/reset/$token/$email");
})->name('password.reset');



