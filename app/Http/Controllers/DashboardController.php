<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisWeek = Carbon::now()->startOfWeek();

        // Statistik Umum
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $siswaAktif = Siswa::where('status', 'aktif')->count();
        $siswaPending = Siswa::where('status', 'pending')->count();

        // Absensi Hari Ini
        $absensiHariIni = Absensi::whereDate('tanggal', $today)->count();
        $masukHariIni = Absensi::where('jenis', 'masuk')->whereDate('tanggal', $today)->count();
        $pulangHariIni = Absensi::where('jenis', 'pulang')->whereDate('tanggal', $today)->count();
        $izinHariIni = Absensi::where('jenis', 'izin')->whereDate('tanggal', $today)->count();

        // Status Absensi Hari Ini
        $hadirHariIni = Absensi::where('status', 'hadir')->whereDate('tanggal', $today)->count();
        $terlambatHariIni = Absensi::where('status', 'terlambat')->whereDate('tanggal', $today)->count();
        $sakitHariIni = Absensi::where('status', 'sakit')->whereDate('tanggal', $today)->count();

        // Persentase Kehadiran Hari Ini
        $persentaseHadir = $totalSiswa > 0 ? round(($masukHariIni / $totalSiswa) * 100, 1) : 0;

        // Data untuk Chart - Absensi 7 Hari Terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $masuk = Absensi::where('jenis', 'masuk')->whereDate('tanggal', $date)->count();
            $pulang = Absensi::where('jenis', 'pulang')->whereDate('tanggal', $date)->count();
            
            $chartData[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'masuk' => $masuk,
                'pulang' => $pulang
            ];
        }

        // Data Status Mingguan
        $statusMingguIni = [
            'hadir' => Absensi::where('status', 'hadir')->where('tanggal', '>=', $thisWeek)->count(),
            'terlambat' => Absensi::where('status', 'terlambat')->where('tanggal', '>=', $thisWeek)->count(),
            'izin' => Absensi::where('status', 'izin')->where('tanggal', '>=', $thisWeek)->count(),
            'sakit' => Absensi::where('status', 'sakit')->where('tanggal', '>=', $thisWeek)->count(),
        ];

        // Top 5 Kelas dengan Kehadiran Terbaik (bulan ini)
        $topKelas = DB::table('absensis') // BUKAN 'absensi'
        ->join('siswas', 'absensis.siswa_id', '=', 'siswas.id')
        ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
        ->where('absensis.jenis', 'masuk')
        ->where('absensis.tanggal', '>=', $thisMonth)
        ->groupBy('kelas.id', 'kelas.nama')
        ->select(
            'kelas.nama',
            DB::raw('COUNT(*) as total_hadir'),
            DB::raw('COUNT(DISTINCT siswas.id) as total_siswa')
        )
        ->orderBy('total_hadir', 'desc')
        ->limit(5)
        ->get();
    

        // Absensi Terbaru
        $absensiTerbaru = Absensi::with(['siswa.kelas'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'desc')
            ->limit(10)
            ->get();

        // Pengaturan Hari Ini
        $pengaturan = Pengaturan::where('tanggal', $today->toDateString())->first();

        return view('master.dashboard', compact(
            'totalSiswa',
            'totalKelas', 
            'siswaAktif',
            'siswaPending',
            'absensiHariIni',
            'masukHariIni',
            'pulangHariIni', 
            'izinHariIni',
            'hadirHariIni',
            'terlambatHariIni',
            'sakitHariIni',
            'persentaseHadir',
            'chartData',
            'statusMingguIni',
            'topKelas',
            'absensiTerbaru',
            'pengaturan'
        ));
    }

    // API untuk real-time update
    public function getRealtimeData()
    {
        $today = Carbon::today();
        
        return response()->json([
            'absensi_hari_ini' => Absensi::whereDate('tanggal', $today)->count(),
            'masuk_hari_ini' => Absensi::where('jenis', 'masuk')->whereDate('tanggal', $today)->count(),
            'pulang_hari_ini' => Absensi::where('jenis', 'pulang')->whereDate('tanggal', $today)->count(),
            'izin_hari_ini' => Absensi::where('jenis', 'izin')->whereDate('tanggal', $today)->count(),
            'current_time' => Carbon::now()->format('H:i:s')
        ]);
    }
}