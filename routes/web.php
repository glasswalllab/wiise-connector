<?php

use Illuminate\Support\Facades\Route;
use glasswalllab\wiise-connector\Http\Controllers\AuthController;

Route::get('/signin', 'AuthController@signin');
Route::get('/callback', 'AuthController@callback');