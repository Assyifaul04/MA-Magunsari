<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class RekapBulananExport implements FromView
{
    protected $tahun, $bulan, $kelas, $siswaId;

    public function __construct($tahun, $bulan, $kelas, $siswaId)
    {
        $this->tahun   = $tahun;
        $this->bulan   = $bulan;
        $this->kelas   = $kelas;
        $this->siswaId = $siswaId;
    }

    public function view(): View
    {
        $siswa = Siswa::with('kelas')->find($this->siswaId);

        $hadir = Absensi::where('siswa_id', $this->siswaId)
            ->whereYear('tanggal', $this->tahun)
            ->whereMonth('tanggal', $this->bulan)
            ->where('status', 'hadir')
            ->count();

        $terlambat = Absensi::where('siswa_id', $this->siswaId)
            ->whereYear('tanggal', $this->tahun)
            ->whereMonth('tanggal', $this->bulan)
            ->where('status', 'terlambat')
            ->count();

        return view('exports.rekap_bulanan', [
            'siswa'      => $siswa,
            'tahun'      => $this->tahun,
            'bulan'      => $this->bulan,
            'hadir'      => $hadir,
            'terlambat'  => $terlambat,
        ]);
    }
}
