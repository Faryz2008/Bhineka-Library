<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
});

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('kategori', KategoriController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['kategori' => 'kategori']);

    Route::resource('buku', BukuController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['buku' => 'buku']);

    Route::post('anggota/import', [AnggotaController::class, 'import'])->name('anggota.import');
    Route::resource('anggota', AnggotaController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['anggota' => 'anggota']);

    Route::resource('peminjaman', PeminjamanController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->parameters(['peminjaman' => 'peminjaman']);

    Route::get('pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('pengembalian/{peminjaman}', [PengembalianController::class, 'create'])->name('pengembalian.create');
    Route::post('pengembalian/{peminjaman}', [PengembalianController::class, 'store'])->name('pengembalian.store');

    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
});