<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Builder;

class Absensi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'absensis';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'waktu_masuk',
        'waktu_pulang',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Scopes untuk query yang sering digunakan
    public function scopeTanggal($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }

    public function scopeBetweenDates(Builder $query, string $start, string $end): Builder
    {
        return $query->whereBetween('tanggal', [$start, $end]);
    }

    public function scopeWithKeterangan($query, ?string $keterangan)
    {
        if ($keterangan) {
            return $query->where('keterangan', $keterangan);
        }
        return $query;
    }

    public function scopeWithWaktuMasuk($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->whereTime('waktu_masuk', '>=', $from);
        }
        if ($to) {
            $query->whereTime('waktu_masuk', '<=', $to);
        }
        return $query;
    }

    public function scopeWithWaktuPulang($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->whereTime('waktu_pulang', '>=', $from);
        }
        if ($to) {
            $query->whereTime('waktu_pulang', '<=', $to);
        }
        return $query;
    }
}