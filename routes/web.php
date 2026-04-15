<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Control;
use Illuminate\Support\Facades\Route;

Route::get('/', [Control::class, 'dashboard'])->name('root');
Route::get('/dashboard', [Control::class, 'dashboard'])->name('dashboard');
Route::get('/kelas-saya', [Control::class, 'classInventory'])->name('class.inventory');

Route::get('/login', [Control::class, 'showLoginForm'])->name('login');
Route::post('/login', [Control::class, 'processLogin'])->name('login.process');
Route::post('/logout', [Control::class, 'logout'])->name('logout');

Route::prefix('chatbot')->group(function () {
    Route::get('/context', [ChatbotController::class, 'context'])->name('chatbot.context');
    Route::post('/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');
    Route::post('/reset', [ChatbotController::class, 'reset'])->name('chatbot.reset');
});
