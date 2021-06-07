<?php

use Illuminate\Support\Facades\Route;
use glasswalllab\wiiseConnector\Http\Controllers\AuthController;

Route::get('/signin', [AuthController::class, 'signin']);
Route::get('/callback', [AuthController::class, 'callback']);