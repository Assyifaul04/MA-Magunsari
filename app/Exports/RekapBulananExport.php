<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class RekapBulananExport implements FromView, ShouldAutoSize
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
        // Hitung jumlah hari dalam bulan terpilih
        $jumlahHari = Carbon::createFromDate($this->tahun, $this->bulan, 1)->daysInMonth;

        // Ambil list siswa berdasarkan filter kelas/siswa
        $querySiswa = Siswa::with('kelas');
        if ($this->kelas) {
            $querySiswa->where('kelas_id', $this->kelas);
        }
        if ($this->siswaId) {
            $querySiswa->where('id', $this->siswaId);
        }
        $siswaList = $querySiswa->orderBy('nama', 'asc')->get();

        // Ambil semua data absensi di bulan & tahun tersebut
        $absensiData = Absensi::whereYear('tanggal', $this->tahun)
            ->whereMonth('tanggal', $this->bulan)
            ->get()
            ->groupBy('siswa_id');

        $rekapData = [];

        foreach ($siswaList as $siswa) {
            $hadir = 0;
            $izin = 0;
            $alfa = 0;
            $terlambat = 0;
            $detailHarian = [];

            for ($hari = 1; $hari <= $jumlahHari; $hari++) {
                $tanggalStr = Carbon::createFromDate($this->tahun, $this->bulan, $hari)->toDateString();
                
                // Cari data absen siswa di tanggal ini
                $absen = optional($absensiData[$siswa->id] ?? collect())->firstWhere('tanggal', $tanggalStr);

                if ($absen) {
                    $status = $absen->status;
                    if ($status === 'hadir') {
                        $detailHarian[$hari] = 'Hadir';
                        $hadir++;
                    } elseif ($status === 'terlambat') {
                        $detailHarian[$hari] = 'Hadir'; // Masuk hitungan hadir di rekap utama gambar
                        $hadir++;
                        $terlambat++; // Opsional jika ingin ditrack terpisah
                    } elseif (in_array($status, ['izin', 'sakit'])) {
                        $detailHarian[$hari] = 'Izin';
                        $izin++;
                    } else {
                        $detailHarian[$hari] = 'Alfa';
                        $alfa++;
                    }
                } else {
                    // Jika tidak ada record absensi
                    $detailHarian[$hari] = ''; 
                }
            }

            $rekapData[] = [
                'nama' => $siswa->nama,
                'detail' => $detailHarian,
                'total_hadir' => $hadir,
                'total_izin' => $izin,
                'total_alfa' => $alfa
            ];
        }

        $namaBulan = Carbon::create()->month((int)$this->bulan)->translatedFormat('F');

        return view('exports.rekap_bulanan', [
            'rekapData'  => $rekapData,
            'jumlahHari' => $jumlahHari,
            'bulan'      => $namaBulan,
            'tahun'      => $this->tahun,
        ]);
    }
}