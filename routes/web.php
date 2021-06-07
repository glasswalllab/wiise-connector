<?php

use Illuminate\Support\Facades\Route;
use glasswalllab\wiise_connector\Http\Controllers\AuthController;

Route::get('/signin', [AuthController::class, 'signin']);
Route::get('/callback', [AuthController::class, 'callback']);