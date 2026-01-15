<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;

Route::get('/', [IndexController::class,'index'], 'index')->name('home');


Route::get('/login', [ UserController::class , 'login' ])->name('login'); 
Route::post('/login' , [ UserController::class , 'loginUser' ])->name('login.user');
Route::get('/register' , [ UserController::class , 'register' ])->name('register');
Route::post('/register' , [ UserController::class , 'registerUser' ])->name('register.user');