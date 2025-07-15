<?php

use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AbsensiController::class, 'index'])->name('import.form');
Route::post('/import', [AbsensiController::class, 'importExcel'])->name('import.excel');
