<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanWa extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_wa';

    protected $fillable = [
        'fonnte_token',
        'nama_perangkat',
        'nomor_wa',
        'status_koneksi',
        'sisa_kuota',
        'masa_aktif',
    ];
}