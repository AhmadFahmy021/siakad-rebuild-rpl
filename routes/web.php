<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TataUsahaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'redirectToLogin']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->middleware(['check.admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard.admin');

        Route::prefix('/kelola')->group(function () {
            Route::resource('/guru', GuruController::class);
            Route::resource('/tu', TataUsahaController::class);
            Route::resource('/siswa', SiswaController::class);
            Route::resource('/user', UserController::class);
        });
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
