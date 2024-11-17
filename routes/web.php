<?php

use \App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('support');
});

Route::get('/login', function () {
    return view('logon');
});



Route::resource('agent',UsersController::class);


