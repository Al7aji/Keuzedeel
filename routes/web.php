<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
Route::get('/', function () {
    return view('index');
});

Route::get('/login', [ UserController::class , 'login' ])->name('login'); 
Route::post('/login' , [ UserController::class , 'loginUser' ])->name('login.user');
Route::get('/register' , [ UserController::class , 'register' ])->name('register');
Route::post('/register' , [ UserController::class , 'registerUser' ])->name('register.user');