<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'redirectToLogin']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->middleware(['check.admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard.admin');
    });

    Route::prefix('guru')->middleware(['check.guru'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexGuru'])->name('dashboard.guru');
    });

    Route::prefix('siswa')->middleware(['check.siswa'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexSiswa'])->name('dashboard.siswa');
    });

    Route::prefix('tu')->middleware(['check.tu'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexTataUsaha'])->name('dashboard.tu');
    });
});
