<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Tambahkan guru_id ke fillable
    protected $fillable = ['nama', 'guru_id'];

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    // Relasi untuk mengambil data Wali Kelas
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}