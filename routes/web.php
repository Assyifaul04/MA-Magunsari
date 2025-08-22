<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengaturanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::get('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
Route::get('/absensi/check-jenis', [AbsensiController::class, 'checkJenis'])->name('absensi.checkJenis');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticated']);
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'chaceLogout'])->group(function () {
    Route::get('master/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('kelas')->group(function () {
        Route::get('/', [KelasController::class, 'index'])->name('kelas.index');
        Route::post('store', [KelasController::class, 'store'])->name('kelas.store');
        Route::post('update/{id}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('delete/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    });

    Route::prefix('siswa')->group(function () {
        Route::get('/', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
        Route::post('scan', [SiswaController::class, 'scan'])->name('siswa.scan');
        Route::post('import', [SiswaController::class, 'import'])->name('siswa.import');
    });

    Route::prefix('pengaturan')->group(function () {
        Route::get('/', [PengaturanController::class, 'edit'])->name('pengaturan.edit');
        Route::post('update', [PengaturanController::class, 'update'])->name('pengaturan.update');
        Route::get('check-jam-masuk', [PengaturanController::class, 'checkJamMasuk'])->name('pengaturan.checkJamMasuk');
    });

    Route::prefix('absensi')->group(function () {
        Route::get('masuk', [AbsensiController::class, 'masuk'])->name('absensi.masuk');
        Route::get('keluar', [AbsensiController::class, 'keluar'])->name('absensi.keluar');
        Route::get('izin', [AbsensiController::class, 'izin'])->name('absensi.izin');

        Route::get('hari-ini', [AbsensiController::class, 'hariIni'])->name('absensi.hariIni');
        Route::get('by-range', [AbsensiController::class, 'byRange'])->name('absensi.byRange');
        Route::get('by-range/export', [AbsensiController::class, 'export'])->name('absensi.export');
        Route::get('by-range/print', [AbsensiController::class, 'print'])->name('absensi.print');

        Route::get('performa', [AbsensiController::class, 'performa'])->name('absensi.performa');
    });
});
