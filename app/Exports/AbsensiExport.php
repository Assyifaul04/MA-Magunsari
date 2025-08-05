<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport implements FromCollection, WithHeadings
{
    protected $tanggal;
    protected $kelas;
    protected $status;

    public function __construct($tanggal, $kelas = null, $status = null)
    {
        $this->tanggal = $tanggal;
        $this->kelas = $kelas;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Absensi::with('siswa')
            ->where('tanggal', $this->tanggal);

        if ($this->kelas) {
            $query->whereHas('siswa', function ($q) {
                $q->where('kelas', $this->kelas);
            });
        }

        if ($this->status === 'hadir' || $this->status === 'terlambat') {
            $query->where('keterangan', $this->status);
        }

        return $query->get()->map(function ($a) {
            return [
                'Nama' => $a->siswa->nama,
                'Kelas' => $a->siswa->kelas,
                'Tanggal' => $a->tanggal,
                'Masuk' => $a->waktu_masuk,
                'Pulang' => $a->waktu_pulang,
                'Keterangan' => ucfirst($a->keterangan),
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama', 'Kelas', 'Tanggal', 'Masuk', 'Pulang', 'Keterangan'];
    }
}
