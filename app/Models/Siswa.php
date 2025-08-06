<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Builder;

class Siswa extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'siswas';

    protected $fillable = [
        'nama',
        'kelas',
        'rfid_uid',
        // 'nis' // Tambahan field NIS jika diperlukan
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%")
                    ->orWhere('rfid_uid', 'like', "%{$search}%");
    }

    // Scope untuk filter kelas
    public function scopeFilterKelas($query, $kelas)
    {
        if ($kelas) {
            return $query->where('kelas', $kelas);
        }
        return $query;
    }
}