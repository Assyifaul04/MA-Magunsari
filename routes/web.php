<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengaturanController;
use Illuminate\Support\Facades\Route;


// route/web.php

// Halaman scan (akses langsung tanpa login)
Route::get('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
Route::get('/absensi/check-jenis', [AbsensiController::class, 'checkJenis'])->name('absensi.checkJenis');


// Routes untuk guest
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticated']);
});

// Logout
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Routes untuk user yang sudah login
Route::middleware(['auth', 'chaceLogout'])->group(function () {

    // Dashboard
    Route::get('master/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', function () {
        return view('welcome'); // atau redirect ke absensi
    });

    // Master Data Kelas
    Route::get('kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::post('kelas/store', [KelasController::class, 'store'])->name('kelas.store');
    Route::post('kelas/update/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('kelas/delete/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');

    // Master Data Siswa
    Route::get('siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
    Route::post('siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::get('siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::put('siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::post('siswa/scan', [SiswaController::class, 'scan'])->name('siswa.scan');
    Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    // Route::get('siswa/export', [SiswaController::class, 'export'])->name('siswa.export');

    // Pengaturan
    Route::get('pengaturan', [PengaturanController::class, 'edit'])->name('pengaturan.edit');
    Route::post('pengaturan/update', [PengaturanController::class, 'update'])->name('pengaturan.update');
    Route::get('/pengaturan/check-jam-masuk', [PengaturanController::class, 'checkJamMasuk'])
    ->name('pengaturan.checkJamMasuk');

    // Presensi (khusus tampilan laporan untuk admin/guru)
    Route::get('absensi/masuk', [AbsensiController::class, 'masuk'])->name('absensi.masuk');
    Route::get('absensi/keluar', [AbsensiController::class, 'keluar'])->name('absensi.keluar');
    Route::get('absensi/izin', [AbsensiController::class, 'izin'])->name('absensi.izin');

    // Data Absensi
    Route::get('absensi/hari-ini', [AbsensiController::class, 'hariIni'])->name('absensi.hariIni');
    Route::get('absensi/by-range', [AbsensiController::class, 'byRange'])->name('absensi.byRange');

    Route::get('absensi/performa', [AbsensiController::class, 'performa'])->name('absensi.performa');

});
