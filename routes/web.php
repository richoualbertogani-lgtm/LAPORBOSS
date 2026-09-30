<?php

use App\Http\Controllers\Admin\AspirasiController as AdminAspirasiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Halaman awal.
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute siswa (guest): tanpa login. Pengiriman dibatasi 10x per menit per pengguna.
Route::get('/aspirasi/buat', [AspirasiController::class, 'create'])->name('aspirasi.create');
Route::post('/aspirasi', [AspirasiController::class, 'store'])->middleware('throttle:10,1')->name('aspirasi.store');
Route::get('/lacak/{kode?}', [AspirasiController::class, 'lacak'])->name('aspirasi.lacak');

// Rute admin: berawalan /admin, halaman pengelolaan wajib login admin.
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'store'])->name('login.store');
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('kategori', KategoriController::class)->except(['show']);

        // Pengelolaan aspirasi: daftar, detail (otomatis "dibaca"), dan ubah status.
        Route::get('/aspirasi', [AdminAspirasiController::class, 'index'])->name('aspirasi.index');
        Route::get('/aspirasi/{aspirasi}', [AdminAspirasiController::class, 'show'])->name('aspirasi.show');
        Route::patch('/aspirasi/{aspirasi}/status', [AdminAspirasiController::class, 'updateStatus'])->name('aspirasi.status');
    });
});
