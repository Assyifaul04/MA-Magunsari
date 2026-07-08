<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn',
        'nama',
        'kelas_id',
        'orang_tua_id',
        'angkatan',
        'rfid',
        'status'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'orang_tua_id');
    }

    // ==== SCOPE UNTUK FILTER STATUS ====

    // Siswa yang masih aktif (termasuk pending)
    public function scopeAktif(Builder $query)
    {
        return $query->whereIn('status', ['aktif', 'pending']);
    }

    // Siswa yang sudah lulus / non_aktif
    public function scopeNon_aktif(Builder $query)
    {
        return $query->where('status', 'non_aktif');
    }
    // Filter berdasarkan angkatan tertentu
    public function scopeAngkatan(Builder $query, $tahun)
    {
        return $query->where('angkatan', $tahun);
    }

    // Helper cek status
    public function isNon_aktif(): bool
    {
        return $this->status === 'non_aktif';
    }
}