<?php

use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\LaporanRfidAdminController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\OrangTuaController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\TemplateWhatsappController;
use App\Http\Controllers\Admin\NotifikasiWhatsappController;
use App\Http\Controllers\Admin\PengaturanWaController;
use App\Http\Controllers\Guru\AbsensiGuruController;
use App\Http\Controllers\Guru\DashboardGuruController;
use App\Http\Controllers\Guru\RfidGuruController;
use App\Http\Controllers\Guru\SiswaGuruController;
// use App\Http\Controllers\Super\SuperAdminController;
// use App\Http\Controllers\Super\TambahUserController;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::get('/test-wa', function () {

    $wa = new WhatsappService();

    return $wa->send(
        '62895371034607',
        'Test WhatsApp RFID berhasil'
    );
});

Route::get('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
Route::get('/absensi/check-jenis', [AbsensiController::class, 'checkJenis'])->name('absensi.checkJenis');
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticated']);
});
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// Route::middleware(['auth', 'chaceLogout', 'role:superAdmin'])->group(function () {
//     // Super Admin Web Routes
//     Route::prefix('superAdmin')->group(function () {
//         Route::get('dashboard', [SuperAdminController::class, 'index'])->name('superAdmin.dashboard');
//         Route::resource('users', TambahUserController::class);
//     });

//     // API Routes for AJAX requests
//     Route::prefix('api')->group(function () {
//         Route::get('user-count', [SuperAdminController::class, 'getUserCount'])->name('api.user-count');
//         Route::get('recent-activities', [SuperAdminController::class, 'getRecentActivities'])->name('api.recent-activities');
//     });
// });


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

        Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
            Route::get('/', [NotifikasiController::class, 'index'])->name('index');
            Route::post('/mark-all-read', [NotifikasiController::class, 'markAllRead'])->name('markAllRead');

            // WAJIB DITAMBAHKAN: Route untuk tombol Hapus
            Route::delete('/{id}', [NotifikasiController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('siswa')->group(function () {
            Route::get('/', [SiswaController::class, 'index'])->name('siswa.index');
            Route::get('create', [SiswaController::class, 'create'])->name('siswa.create');
            Route::post('/', [SiswaController::class, 'store'])->name('siswa.store');

            // Rute statis HARUS di atas rute wildcard {siswa}
            Route::get('non_aktif', [SiswaController::class, 'non_aktif'])->name('siswa.non_aktif');
            Route::get('luluskan', [SiswaController::class, 'formLuluskanAngkatan'])->name('siswa.luluskan.form');
            Route::post('luluskan', [SiswaController::class, 'luluskanAngkatan'])->name('siswa.luluskan');
            Route::post('{siswa}/batalkan-non_aktif', [SiswaController::class, 'batalkanAlumni'])->name('siswa.batalkan-non_aktif');

            Route::get('{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
            Route::put('{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
            Route::delete('{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
            Route::post('scan', [SiswaController::class, 'scan'])->name('siswa.scan');
            Route::post('import', [SiswaController::class, 'import'])->name('siswa.import');
        });

        Route::prefix('guru')->group(function () {
            Route::get('/', [GuruController::class, 'index'])->name('guru.index');
            Route::post('store', [GuruController::class, 'store'])->name('guru.store');
            Route::put('update/{guru}', [GuruController::class, 'update'])->name('guru.update');
            Route::delete('delete/{guru}', [GuruController::class, 'destroy'])->name('guru.destroy');
        });

        Route::prefix('pengaturan')->group(function () {
            Route::get('/', [PengaturanController::class, 'edit'])->name('pengaturan.edit');
            Route::post('update', [PengaturanController::class, 'update'])->name('pengaturan.update');
            Route::get('check-jam-masuk', [PengaturanController::class, 'checkJamMasuk'])->name('pengaturan.checkJamMasuk');
        });

        Route::prefix('pengaturan-wa')->group(function () {
            Route::get('/', [PengaturanWaController::class, 'index'])->name('pengaturan-wa.index');
            Route::post('/update', [PengaturanWaController::class, 'updateToken'])->name('pengaturan-wa.update');
            Route::post('/disconnect', [PengaturanWaController::class, 'disconnectDevice'])->name('pengaturan-wa.disconnect');
            
            // UBAH .name() di bawah ini menjadi 'pengaturan-wa.hapus-token'
            Route::post('/hapus', [PengaturanWaController::class, 'hapusToken'])->name('pengaturan-wa.hapus-token');
            Route::get('/cek-status', [PengaturanWaController::class, 'cekStatus'])->name('pengaturan-wa.cek-status');
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
            Route::get('rekap/export', [AbsensiController::class, 'exportExcel'])->name('rekap.export');
        });

        Route::prefix('orang-tua')->group(function () {
            Route::get('/', [OrangTuaController::class, 'index'])->name('orangtua.index');
            Route::get('create', [OrangTuaController::class, 'create'])->name('orangtua.create');
            Route::post('/', [OrangTuaController::class, 'store'])->name('orangtua.store');
            Route::get('{orangTua}/edit', [OrangTuaController::class, 'edit'])->name('orangtua.edit');
            Route::put('{orangTua}', [OrangTuaController::class, 'update'])->name('orangtua.update');
            Route::delete('{orangTua}', [OrangTuaController::class, 'destroy'])->name('orangtua.destroy');
            Route::post('orangtua/import', [OrangTuaController::class, 'import'])->name('orangtua.import');
        });

        Route::prefix('template-whatsapp')->group(function () {

            Route::get('/', [TemplateWhatsappController::class, 'index'])->name('templatewa.index');
            Route::post('generate', [TemplateWhatsappController::class, 'generateDefault'])->name('templatewa.generate');
            Route::get('create', [TemplateWhatsappController::class, 'create'])->name('templatewa.create');
            Route::post('/', [TemplateWhatsappController::class, 'store'])->name('templatewa.store');
            Route::get('{template}/edit', [TemplateWhatsappController::class, 'edit'])->name('templatewa.edit');
            Route::put('{template}', [TemplateWhatsappController::class, 'update'])->name('templatewa.update');
            Route::delete('{template}', [TemplateWhatsappController::class, 'destroy'])->name('templatewa.destroy');
        });

        Route::prefix('notifikasi-whatsapp')->group(function () {
            Route::get('/', [NotifikasiWhatsappController::class, 'index'])->name('notifikasiwa.index');
            Route::get('{notifikasi}', [NotifikasiWhatsappController::class, 'show'])->name('notifikasiwa.show');
            Route::delete('{notifikasi}', [NotifikasiWhatsappController::class, 'destroy'])->name('notifikasiwa.destroy');
        });

        Route::prefix('rfid')->name('rfid.')->group(function () {
            Route::get('/laporan-hilang', [LaporanRfidAdminController::class, 'index'])->name('laporan-hilang');
            Route::get('/notifikasi/{id}', [LaporanRfidAdminController::class, 'readNotification'])->name('notifikasi.read');
        });
    });
});


Route::middleware(['auth', 'chaceLogout', 'role:guru'])->group(function () {
    Route::prefix('guru')->name('guru.')->group(function () {
        // Dashboard Utama
        Route::get('dashboard', [DashboardGuruController::class, 'index'])->name('dashboard');

        // Manajemen & Monitoring Siswa + Absensi RFID
        Route::prefix('siswa')->name('siswa.')->group(function () {
            Route::get('/', [SiswaGuruController::class, 'index'])->name('index'); // Lihat daftar siswa & status RFID
            Route::get('/{siswa}', [SiswaGuruController::class, 'show'])->name('show'); // Detail absensi per siswa
        });

        Route::prefix('absensi')->name('absensi.')->group(function () {
            Route::get('/hari-ini', [AbsensiGuruController::class, 'hariIni'])->name('hari-ini'); // Live monitoring hari ini
            Route::post('/manual-update/{id}', [AbsensiGuruController::class, 'updateManual'])->name('update-manual'); // Override Izin/Sakit/Kartu Ketinggalan
            Route::get('/rekap', [AbsensiGuruController::class, 'rekap'])->name('rekap');
            Route::get('/rekap/export', [AbsensiGuruController::class, 'exportExcel'])->name('rekap.export');
        });

        Route::prefix('rfid')->name('rfid.')->group(function () {
            Route::get('/belum-terdaftar', [RfidGuruController::class, 'belumTerdaftar'])->name('belum-terdaftar');

            Route::get('/laporan-hilang', [RfidGuruController::class, 'laporanHilang'])->name('laporan-hilang');
            Route::post('/laporan-hilang/{id}', [RfidGuruController::class, 'submitLaporanHilang'])->name('submit-laporan-hilang');
        });
    });
});
