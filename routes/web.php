<?php

use App\Http\Controllers\Control;
use Illuminate\Support\Facades\Route;

Route::get('/', [Control::class, 'home'])->name('root');
Route::get('/home', [Control::class, 'home'])->name('home');

Route::get('/login', [Control::class, 'showLoginForm'])->name('login');
Route::post('/login', [Control::class, 'processLogin'])->name('login.process');
Route::post('/logout', [Control::class, 'logout'])->name('logout');
