<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IssueController;



Route::group(['prefix' => '/user'], function () {
    Route::get('/token', [UserController::class, 'token']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::put('/name', [UserController::class, 'upname']);
});
Route::group(['prefix' => '/issue'], function () {
    Route::post('/create', [IssueController::class, 'create']);
    Route::post('/list', [IssueController::class, 'list']);
});
Route::group(['prefix' => '/issue/comment'], function () {
    Route::post('/create', [IssueController::class, 'commentCreate']);
    Route::post('/list', [IssueController::class, 'commentList']);
});
