<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Siswa extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'siswas';
    protected $fillable = ['nama', 'kelas', 'rfid_uid'];

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}

