<?php

use Illuminate\Support\Facades\Route;
use glasswalllab\wiiseconnector\Http\Controllers\AuthController;

Route::group(['middleware' => ['web']], function () {
    Route::get('/wiise/signin', [AuthController::class, 'signin']);
    Route::get('/wiise/callback', [AuthController::class, 'callback']);
    Route::get('/wiise/signout/{provider}', [AuthController::class, 'signout']);
});
