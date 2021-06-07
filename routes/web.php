<?php

use Illuminate\Support\Facades\Route;

Route::get('/signin', 'AuthController@signin');
Route::get('/callback', 'AuthController@callback');