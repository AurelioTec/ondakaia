<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::view('/termos', 'legal.terms')
    ->name('legal.terms');

Route::view('/privacidade', 'legal.privacy')
    ->name('legal.privacy');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [ChatController::class, 'index'])
        ->name('chat.index');

    Route::post('/conversar', [ChatController::class, 'store'])
        ->name('chat.store');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
