<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeuzedeelController;

Route::get('/', [IndexController::class,'index'], 'index')->name('home');

Route::get('/dashboard', [DashboardController::class,'index'])->name('admin');
Route::get('/dashboard/keuezedeel', [DashboardController::class,'keuzedeel'])->name('dashboard.keuezedeel');

Route::get('/login', [ UserController::class , 'login' ])->name('login'); 
Route::post('/login' , [ UserController::class , 'loginUser' ])->name('login.user');
Route::get('/register' , [ UserController::class , 'register' ])->name('register');
Route::post('/register' , [ UserController::class , 'registerUser' ])->name('register.user');


Route::get('/Keuzedeels', [ KeuzedeelController::class ,'index'])->name('Keuzedeel');
Route ::get('/Keuzedeels/create', [ KeuzedeelController::class ,'create'])->name('Keuzedeel.create');
Route ::post('/Keuzedeels', [ KeuzedeelController::class ,'store'])->name('Keuzedeel.store');
Route ::get('/Keuzedeels/{Keuzedeel}/edit', [ KeuzedeelController::class ,'edit'])->name('Keuzedeel.edit');
Route ::put('/Keuzedeels/{Keuzedeel}', [ KeuzedeelController::class ,'update'])->name('Keuzedeel.update');
Route ::delete('/Keuzedeels/{Keuzedeel}', [ KeuzedeelController::class ,'delete'])->name('Keuzedeel.destroy');
