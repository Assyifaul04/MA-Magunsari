<?php

use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Guru\DashboardGuruController;
use App\Http\Controllers\Super\SuperAdminController;
use App\Http\Controllers\Super\TambahUserController;
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


Route::middleware(['auth', 'chaceLogout', 'role:superAdmin'])->group(function () {
    // Super Admin Web Routes
    Route::prefix('superAdmin')->group(function () {
        Route::get('dashboard', [SuperAdminController::class, 'index'])->name('superAdmin.dashboard');
        Route::resource('users', TambahUserController::class);
    });
    
    // API Routes for AJAX requests
    Route::prefix('api')->group(function () {
        Route::get('user-count', [SuperAdminController::class, 'getUserCount'])->name('api.user-count');
        Route::get('recent-activities', [SuperAdminController::class, 'getRecentActivities'])->name('api.recent-activities');
    });
});


Route::middleware(['auth', 'chaceLogout', 'role:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        Route::prefix('admins')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('admins.index');
            Route::post('store', [AdminController::class, 'store'])->name('admins.store');
            Route::get('{user}/edit', [AdminController::class, 'edit'])->name('admins.edit');
            Route::put('{user}', [AdminController::class, 'update'])->name('admins.update');
            Route::delete('{user}', [AdminController::class, 'destroy'])->name('admins.destroy');
        });
        

        Route::prefix('kelas')->group(function () {
            Route::get('/', [KelasController::class, 'index'])->name('kelas.index');
            Route::post('store', [KelasController::class, 'store'])->name('kelas.store');
            Route::put('update/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
            Route::delete('delete/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');
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
            Route::get('check-jenis', [AbsensiController::class, 'checkJenis'])->name('absensi.checkJenis');
            Route::post('store', [AbsensiController::class, 'store'])->name('absensi.store');

            Route::get('hari-ini', [AbsensiController::class, 'hariIni'])->name('absensi.hariIni');
            Route::get('by-range', [AbsensiController::class, 'byRange'])->name('absensi.byRange');
            Route::get('by-range/export', [AbsensiController::class, 'export'])->name('absensi.export');
            Route::get('by-range/print', [AbsensiController::class, 'print'])->name('absensi.print');
            Route::get('rekap-bulanan', [AbsensiController::class, 'rekapBulanan'])->name('absensi.rekap_bulanan');
        });
    });
});


Route::middleware(['auth', 'chaceLogout', 'role:guru'])->group(function () {
    Route::prefix('guru')->group(function () {
        Route::get('dashboard', [DashboardGuruController::class, 'index'])->name('guru.dashboard');
    });
});
