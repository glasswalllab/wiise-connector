<?php

use Illuminate\Support\Facades\Route;
use glasswalllab\wiiseconnector\Http\Controllers\AuthController;

Route::group(['middleware' => ['web']], function () {
    Route::get('/signin', [AuthController::class, 'signin']);
    Route::get('/callback', [AuthController::class, 'callback']);
});
