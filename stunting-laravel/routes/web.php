<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StuntingPredictionController;
use Illuminate\Support\Facades\Route;

// Redirect halaman utama ke dashboard (auth) atau login (guest)
Route::get('/', function () {
    return redirect()->to(auth()->check() ? route('dashboard') : route('login'));
});

// ================== Guest only ==================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// ================== Authenticated only ==================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('stunting')->name('stunting.')->group(function () {
        Route::get('/',           [StuntingPredictionController::class, 'index'])->name('index');
        Route::get('/create',     [StuntingPredictionController::class, 'create'])->name('create');
        Route::post('/',          [StuntingPredictionController::class, 'store'])->name('store');
        Route::get('/{stunting}', [StuntingPredictionController::class, 'show'])->name('show');
    });
});
