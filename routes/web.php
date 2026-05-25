<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TagihanController;
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
            Route::resource('/account/admin', AdminController::class);
        });
        Route::resource('/pembayaran', PembayaranController::class);
        Route::resource('/bank', BankController::class);
        Route::resource('/tagihan', TagihanController::class);

    });

    Route::prefix('guru')->middleware(['check.guru'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexGuru'])->name('dashboard.guru');
    });

    Route::prefix('siswa')->middleware(['check.siswa'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexSiswa'])->name('dashboard.siswa');
        Route::get('/tugas', [TugasController::class, 'index'])->name('siswa.tugas.index');
        Route::post('/tugas/{tugas}/kumpul', [TugasController::class, 'storeSubmission'])->name('siswa.tugas.submit');
        Route::get('/nilai', [NilaiController::class, 'index'])->name('siswa.nilai.index');
        Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('siswa.konsultasi');
    });

    Route::prefix('tu')->middleware(['check.tu'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexTataUsaha'])->name('dashboard.tu');
    });
});
