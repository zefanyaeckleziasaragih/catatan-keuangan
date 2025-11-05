<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinanceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::group(['prefix' => 'app', 'middleware' => 'check.auth'], function () {
    Route::get('/finance', [FinanceController::class, 'index'])->name('app.finance');
    Route::get('/statistics', [FinanceController::class, 'statistics'])->name('app.statistics');
});

Route::get('/', function () {
    return redirect()->route('app.finance');
});