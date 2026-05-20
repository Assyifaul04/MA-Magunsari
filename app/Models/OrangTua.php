<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nomor_whatsapp',
        'alamat'
    ];

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
}
