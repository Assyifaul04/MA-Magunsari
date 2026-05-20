<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateWhatsapp extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_template',
        'jenis',
        'isi_pesan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
