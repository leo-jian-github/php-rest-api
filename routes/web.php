<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get(uri: "/token", action: [UserController::class, "token"]);
Route::post(uri: "/user/register", action: [UserController::class, "register"]);

