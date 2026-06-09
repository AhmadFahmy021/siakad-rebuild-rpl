<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthOrtuController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\MataPelajaranController;
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

Route::get('ortu/login', [AuthOrtuController::class, 'index']);
Route::post('ortu/login', [AuthOrtuController::class, 'login']);

Route::middleware(['check.orangtua'])->prefix('ortu')->group(function () {
    Route::get('logout', [AuthOrtuController::class, 'logout']);
    Route::get('/dashboard', function () {
        return view('ortu.index');
    });
    Route::prefix('pembayaran')->group(function () {
        Route::get('/', [PembayaranController::class, 'indexOrtu']);
        Route::get('/{tagihan}/bayar', [PembayaranController::class, 'bayar']);
        Route::post('/{tagihan}', [PembayaranController::class, 'bayarStore']);
    });
    Route::get('jadwal', [JadwalController::class, 'indexOrtu']);
});

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

        Route::get('/ajax/pembayaran/siswa/{kelasId}', [PembayaranController::class, 'getSiswaByKelas']);
        Route::get('/ajax/pembayaran/tagihan/{kelasId}', [PembayaranController::class, 'getTagihanByKelasId']);
    });

    Route::prefix('guru')->middleware(['check.guru'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexGuru'])->name('dashboard.guru');
        Route::get('/walas', [DashboardController::class, 'indexWalas'])->name('guru.walas.index');
        Route::get('/walas/siswa/{siswaId}', [DashboardController::class, 'showWalasSiswa'])->name('guru.walas.siswa');
        Route::post('/walas/siswa/{siswaId}/catatan', [DashboardController::class, 'storeWalasCatatan'])->name('guru.walas.catatan.store');
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

        Route::resource('/kelas', KelasController::class) ->parameters(['kelas' => 'kelas']);
        Route::get('/kelas/{kelas}/kelola', [KelasController::class, 'kelola']);
        Route::resource('/pembayaran', PembayaranController::class);
        Route::resource('/matapelajaran', MataPelajaranController::class)->parameters(['matapelajaran' => 'mataPelajaran']);
        Route::resource('/jadwal', JadwalController::class)->parameters(['jadwal' => 'jadwal']);
    });
});
