<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifikasiWhatsapp extends Model
{
    use HasFactory;

    protected $fillable = [
        'absensi_id',
        'siswa_id',
        'orang_tua_id',
        'nomor_whatsapp',
        'pesan',
        'status',
        'response_gateway',
        'dikirim_pada',
    ];

    protected $casts = [
        'dikirim_pada' => 'datetime',
    ];


    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function absensi()
    {
        return $this->belongsTo(Absensi::class);
    }

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class);
    }
}
