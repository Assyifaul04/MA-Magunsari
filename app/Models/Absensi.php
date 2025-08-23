<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'siswa_id', 'jenis', 'status', 'rfid', 'keterangan', 'tanggal', 'jam'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}

