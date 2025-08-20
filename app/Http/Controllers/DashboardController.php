<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Hari Ini
        $today = Carbon::today();
        $hariIni = $this->getStatistikHariIni($today);
        
        // Statistik Minggu Ini
        $mingguIni = $this->getStatistikMingguIni();
        
        // Statistik Bulan Ini
        $bulanIni = $this->getStatistikBulanIni();
        
        // Data untuk Chart Kehadiran 7 Hari Terakhir
        $chartKehadiran = $this->getChartKehadiran7Hari();
        
        // Data untuk Chart Status Absensi Bulan Ini
        $chartStatus = $this->getChartStatusBulanIni();
        
        // Data untuk Chart Performa Per Kelas
        $chartPerformaKelas = $this->getChartPerformaKelas();
        
        // Siswa Terlambat Hari Ini
        $siswaTerlambat = $this->getSiswaTerlambatHariIni();
        
        // Absensi Terbaru
        $absensiTerbaru = $this->getAbsensiTerbaru();
        
        // Statistik Per Kelas
        $statistikKelas = $this->getStatistikPerKelas();

        return view('master.dashboard', compact(
            'hariIni',
            'mingguIni', 
            'bulanIni',
            'chartKehadiran',
            'chartStatus',
            'chartPerformaKelas',
            'siswaTerlambat',
            'absensiTerbaru',
            'statistikKelas'
        ));
    }

    private function getStatistikHariIni($today)
    {
        $totalSiswa = Siswa::where('status', 'aktif')->count();
        $hadir = Absensi::whereDate('tanggal', $today)
            ->where('jenis', 'masuk')
            ->whereIn('status', ['hadir', 'terlambat'])
            ->distinct('siswa_id')
            ->count();
        $terlambat = Absensi::whereDate('tanggal', $today)
            ->where('jenis', 'masuk')
            ->where('status', 'terlambat')
            ->count();
        $izin = Absensi::whereDate('tanggal', $today)
            ->where('jenis', 'izin')
            ->count();
        $alpha = $totalSiswa - $hadir - $izin;

        return [
            'total_siswa' => $totalSiswa,
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'izin' => $izin,
            'alpha' => max(0, $alpha),
            'persentase_kehadiran' => $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100, 1) : 0
        ];
    }

    private function getStatistikMingguIni()
    {
        $startWeek = Carbon::now()->startOfWeek();
        $endWeek = Carbon::now()->endOfWeek();
        
        return Absensi::whereBetween('tanggal', [$startWeek, $endWeek])
            ->where('jenis', 'masuk')
            ->selectRaw('
                COUNT(CASE WHEN status = "hadir" THEN 1 END) as hadir,
                COUNT(CASE WHEN status = "terlambat" THEN 1 END) as terlambat,
                COUNT(CASE WHEN status = "izin" THEN 1 END) as izin
            ')
            ->first();
    }

    private function getStatistikBulanIni()
    {
        $startMonth = Carbon::now()->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();
        
        return Absensi::whereBetween('tanggal', [$startMonth, $endMonth])
            ->where('jenis', 'masuk')
            ->selectRaw('
                COUNT(CASE WHEN status = "hadir" THEN 1 END) as hadir,
                COUNT(CASE WHEN status = "terlambat" THEN 1 END) as terlambat,
                COUNT(CASE WHEN status = "izin" THEN 1 END) as izin
            ')
            ->first();
    }

    private function getChartKehadiran7Hari()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $hadir = Absensi::whereDate('tanggal', $date)
                ->where('jenis', 'masuk')
                ->whereIn('status', ['hadir', 'terlambat'])
                ->distinct('siswa_id')
                ->count();
            
            $data[] = [
                'tanggal' => $date->format('d/m'),
                'hadir' => $hadir
            ];
        }
        return $data;
    }

    private function getChartStatusBulanIni()
    {
        $startMonth = Carbon::now()->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();
        
        return Absensi::whereBetween('tanggal', [$startMonth, $endMonth])
            ->where('jenis', 'masuk')
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();
    }

    private function getChartPerformaKelas()
    {
        return DB::table('kelas')
            ->leftJoin('siswas', 'kelas.id', '=', 'siswas.kelas_id')
            ->leftJoin('absensis', function($join) {
                $join->on('siswas.id', '=', 'absensis.siswa_id')
                     ->where('absensis.jenis', '=', 'masuk')
                     ->whereMonth('absensis.tanggal', Carbon::now()->month);
            })
            ->selectRaw('
                kelas.nama,
                COUNT(DISTINCT siswas.id) as total_siswa,
                COUNT(CASE WHEN absensis.status = "hadir" THEN 1 END) as hadir,
                COUNT(CASE WHEN absensis.status = "terlambat" THEN 1 END) as terlambat
            ')
            ->groupBy('kelas.id', 'kelas.nama')
            ->get();
    }

    private function getSiswaTerlambatHariIni()
    {
        return Absensi::with('siswa.kelas')
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'terlambat')
            ->where('jenis', 'masuk')
            ->orderBy('jam', 'desc')
            ->take(10)
            ->get();
    }

    private function getAbsensiTerbaru()
    {
        return Absensi::with('siswa.kelas')
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'desc')
            ->take(10)
            ->get();
    }

    private function getStatistikPerKelas()
    {
        return DB::table('kelas')
            ->leftJoin('siswas', 'kelas.id', '=', 'siswas.kelas_id')
            ->leftJoin('absensis', function($join) {
                $join->on('siswas.id', '=', 'absensis.siswa_id')
                     ->whereDate('absensis.tanggal', Carbon::today())
                     ->where('absensis.jenis', 'masuk');
            })
            ->selectRaw('
                kelas.nama,
                COUNT(DISTINCT siswas.id) as total_siswa,
                COUNT(CASE WHEN absensis.status IN ("hadir", "terlambat") THEN 1 END) as hadir,
                COUNT(CASE WHEN absensis.status = "terlambat" THEN 1 END) as terlambat
            ')
            ->groupBy('kelas.id', 'kelas.nama')
            ->get()
            ->map(function($item) {
                $item->alpha = $item->total_siswa - $item->hadir;
                $item->persentase = $item->total_siswa > 0 ? round(($item->hadir / $item->total_siswa) * 100, 1) : 0;
                return $item;
            });
    }
}