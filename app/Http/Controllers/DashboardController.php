<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // Stats Cards
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $siswaAktif = Siswa::where('status', 'aktif')->count();
        $absensiHariIni = Absensi::whereDate('tanggal', $today)->count();

        // Absensi hari ini berdasarkan status
        $absensiHariIniStatus = Absensi::whereDate('tanggal', $today)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Absensi 7 hari terakhir
        $absensi7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $absensi7Hari[] = [
                'tanggal' => $date->format('d M'),
                'hadir' => Absensi::whereDate('tanggal', $date)->where('status', 'hadir')->count(),
                'terlambat' => Absensi::whereDate('tanggal', $date)->where('status', 'terlambat')->count(),
                'izin' => Absensi::whereDate('tanggal', $date)->where('status', 'izin')->count(),
                'pulang' => Absensi::whereDate('tanggal', $date)->where('status', 'pulang')->count(),
            ];
        }

        // Absensi per kelas hari ini
        $absensiPerKelas = Kelas::withCount(['siswa as total_siswa'])
            ->with(['siswa' => function($query) use ($today) {
                $query->whereHas('absensi', function($q) use ($today) {
                    $q->whereDate('tanggal', $today)->where('jenis', 'masuk');
                });
            }])
            ->get()
            ->map(function ($kelas) {
                return [
                    'nama' => $kelas->nama,
                    'total_siswa' => $kelas->total_siswa,
                    'hadir' => $kelas->siswa->count(),
                    'tidak_hadir' => $kelas->total_siswa - $kelas->siswa->count(),
                    'persentase' => $kelas->total_siswa > 0 ? round(($kelas->siswa->count() / $kelas->total_siswa) * 100, 1) : 0
                ];
            });

        // Top 5 siswa terlambat bulan ini
        $siswaSeringTerlambat = Siswa::with('kelas')
            ->withCount(['absensi as terlambat_count' => function($query) use ($thisMonth) {
                $query->where('status', 'terlambat')
                      ->where('tanggal', '>=', $thisMonth);
            }])
            ->having('terlambat_count', '>', 0)
            ->orderBy('terlambat_count', 'desc')
            ->take(5)
            ->get();

        // Absensi masuk vs pulang minggu ini
        $absensiMingguIni = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $thisWeek->copy()->addDays($i);
            $absensiMingguIni[] = [
                'hari' => $date->format('D'),
                'tanggal' => $date->format('d/m'),
                'masuk' => Absensi::whereDate('tanggal', $date)->where('jenis', 'masuk')->count(),
                'pulang' => Absensi::whereDate('tanggal', $date)->where('jenis', 'pulang')->count(),
            ];
        }

        // Pengaturan jam hari ini
        $pengaturanHariIni = Pengaturan::where('tanggal', $today->toDateString())->first();
        if (!$pengaturanHariIni) {
            $pengaturanHariIni = (object) [
                'jam_masuk_awal' => '05:00',
                'jam_masuk_akhir' => '07:00',
                'jam_pulang' => '15:00'
            ];
        }

        // Status waktu absensi saat ini
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');
        $jamMasukAwal = $pengaturanHariIni->jam_masuk_awal ?? '05:00:00';
        $jamMasukAkhir = $pengaturanHariIni->jam_masuk_akhir ?? '07:00:00';
        $jamPulang = $pengaturanHariIni->jam_pulang ?? '15:00:00';

        if ($currentTime < $jamMasukAwal) {
            $statusWaktu = 'Belum waktu masuk';
            $jenisAbsensi = 'masuk';
        } elseif ($currentTime >= $jamMasukAwal && $currentTime <= $jamMasukAkhir) {
            $statusWaktu = 'Waktu masuk';
            $jenisAbsensi = 'masuk';
        } elseif ($currentTime > $jamMasukAkhir && $currentTime < $jamPulang) {
            $statusWaktu = 'Terlambat masuk';
            $jenisAbsensi = 'masuk';
        } else {
            $statusWaktu = 'Waktu pulang';
            $jenisAbsensi = 'pulang';
        }

        return view('master.dashboard', compact(
            'totalSiswa',
            'totalKelas',
            'siswaAktif',
            'absensiHariIni',
            'absensiHariIniStatus',
            'absensi7Hari',
            'absensiPerKelas',
            'siswaSeringTerlambat',
            'absensiMingguIni',
            'pengaturanHariIni',
            'statusWaktu',
            'jenisAbsensi'
        ));
    }
}