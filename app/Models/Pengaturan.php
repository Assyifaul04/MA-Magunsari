<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturans';
    protected $fillable = [
        'tanggal',
        'jam_masuk_awal',
        'jam_masuk_akhir',
        'jam_pulang'
    ];
}
