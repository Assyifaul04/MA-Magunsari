<?php

use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AbsensiController::class, 'index'])->name('import.form');
Route::post('/import', [AbsensiController::class, 'importExcel'])->name('import.excel');
Route::put('/update-uuid/siswa/{id}', [AbsensiController::class, 'updateRfid'])->name('update.rfid');
Route::get('/absensi', [AbsensiController::class, 'absensi']);
Route::post('/create-absensi', [AbsensiController::class, 'createAbsensi'])->name('absensi');
