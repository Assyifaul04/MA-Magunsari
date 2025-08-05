<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Halaman Dashboard Admin
Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Manajemen Siswa (CRUD)
Route::prefix('admin/siswa')->name('siswa.')->group(function () {
    Route::get('/', [SiswaController::class, 'index'])->name('index');
    Route::get('/create', [SiswaController::class, 'create'])->name('create');
    Route::post('/', [SiswaController::class, 'store'])->name('store');
    Route::get('/{siswa}/edit', [SiswaController::class, 'edit'])->name('edit');
    Route::put('/{siswa}', [SiswaController::class, 'update'])->name('update');
    Route::delete('/{siswa}', [SiswaController::class, 'destroy'])->name('destroy');
});

Route::post('admin/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');


// Absensi
Route::prefix('admin/absensi')->name('absensi.')->group(function () {
    Route::get('/', [AbsensiController::class, 'index'])->name('index');
    Route::get('/rekap', [AbsensiController::class, 'rekap'])->name('rekap');
    Route::get('/export', [AbsensiController::class, 'export'])->name('export');

    // lookup oleh frontend kecil (RPC)
    Route::get('/lookup', [AbsensiController::class, 'lookup'])->name('lookup');

    // scan RFID dalam grup agar namanya absensi.scanRfid
    Route::post('/scan-rfid', [AbsensiController::class, 'scanRfid'])->name('scanRfid');
});
