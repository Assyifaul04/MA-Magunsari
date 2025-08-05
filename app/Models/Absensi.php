<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Absensi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'absensis';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'waktu_masuk',
        'waktu_pulang', // <--- Tambahan
        'keterangan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function scopeTanggal($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }

    public function scopeKeterangan($query, $ket)
    {
        if ($ket) return $query->where('keterangan', $ket);
        return $query;
    }
}
