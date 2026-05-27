<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $today     = Carbon::today();
        $thisWeek  = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // ── Stat Cards ──────────────────────────────────────────
        $totalSiswa      = Siswa::count();
        $totalKelas      = Kelas::count();
        $siswaAktif      = Siswa::where('status', 'aktif')->count();
        $siswaPending    = Siswa::where('status', 'pending')->count();
        $absensiHariIni  = Absensi::whereDate('tanggal', $today)->count();

        // Persentase kehadiran hari ini vs total siswa aktif
        $hadirHariIni = Absensi::whereDate('tanggal', $today)
            ->where('jenis', 'masuk')
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();
        $persentaseHadir = $siswaAktif > 0
            ? round(($hadirHariIni / $siswaAktif) * 100, 1)
            : 0;

        // ── Absensi hari ini per status ─────────────────────────
        $absensiHariIniStatus = Absensi::whereDate('tanggal', $today)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // ── Absensi 7 hari terakhir ─────────────────────────────
        $absensi7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $date           = Carbon::today()->subDays($i);
            $absensi7Hari[] = [
                'tanggal'   => $date->format('d M'),
                'hadir'     => Absensi::whereDate('tanggal', $date)->where('status', 'hadir')->count(),
                'terlambat' => Absensi::whereDate('tanggal', $date)->where('status', 'terlambat')->count(),
                'izin'      => Absensi::whereDate('tanggal', $date)->where('status', 'izin')->count(),
                'pulang'    => Absensi::whereDate('tanggal', $date)->where('status', 'pulang')->count(),
                'tidak_hadir' => Absensi::whereDate('tanggal', $date)->where('status', 'tidak hadir')->count(),
            ];
        }

        // ── Absensi per kelas hari ini ──────────────────────────
        $absensiPerKelas = Kelas::withCount(['siswa as total_siswa'])
            ->with(['siswa' => function ($query) use ($today) {
                $query->whereHas('absensi', function ($q) use ($today) {
                    $q->whereDate('tanggal', $today)->where('jenis', 'masuk');
                });
            }])
            ->get()
            ->map(function ($kelas) {
                $hadir = $kelas->siswa->count();
                $total = $kelas->total_siswa;
                return [
                    'nama'        => $kelas->nama,
                    'total_siswa' => $total,
                    'hadir'       => $hadir,
                    'tidak_hadir' => $total - $hadir,
                    'persentase'  => $total > 0 ? round(($hadir / $total) * 100, 1) : 0,
                ];
            });

        // ── Top 5 siswa paling sering terlambat bulan ini ───────
        $siswaSeringTerlambat = Siswa::with('kelas')
            ->withCount(['absensi as terlambat_count' => function ($query) use ($thisMonth) {
                $query->where('status', 'terlambat')
                      ->where('tanggal', '>=', $thisMonth);
            }])
            ->having('terlambat_count', '>', 0)
            ->orderBy('terlambat_count', 'desc')
            ->take(5)
            ->get();

        // ── Absensi masuk vs pulang minggu ini ──────────────────
        $absensiMingguIni = [];
        for ($i = 0; $i < 7; $i++) {
            $date               = $thisWeek->copy()->addDays($i);
            $absensiMingguIni[] = [
                'hari'    => $date->isoFormat('ddd'),
                'tanggal' => $date->format('d/m'),
                'masuk'   => Absensi::whereDate('tanggal', $date)->where('jenis', 'masuk')->count(),
                'pulang'  => Absensi::whereDate('tanggal', $date)->where('jenis', 'pulang')->count(),
            ];
        }

        // ── Tren kehadiran 4 minggu terakhir (persentase) ───────
        $trenKehadiran = [];
        for ($w = 3; $w >= 0; $w--) {
            $weekStart      = Carbon::now()->startOfWeek()->subWeeks($w);
            $weekEnd        = $weekStart->copy()->endOfWeek();
            $totalMasuk     = Absensi::whereBetween('tanggal', [$weekStart->toDateString(), $weekEnd->toDateString()])
                                ->where('jenis', 'masuk')->count();
            $totalHadir     = Absensi::whereBetween('tanggal', [$weekStart->toDateString(), $weekEnd->toDateString()])
                                ->whereIn('status', ['hadir', 'terlambat'])->count();
            $trenKehadiran[] = [
                'label'      => 'W' . ($weekStart->weekOfYear),
                'persentase' => $totalMasuk > 0 ? round(($totalHadir / $totalMasuk) * 100, 1) : 0,
            ];
        }

        // ── Rekap bulan ini (ringkasan angka) ───────────────────
        $rekapBulanIni = [
            'hadir'      => Absensi::where('tanggal', '>=', $thisMonth)->where('status', 'hadir')->count(),
            'terlambat'  => Absensi::where('tanggal', '>=', $thisMonth)->where('status', 'terlambat')->count(),
            'izin'       => Absensi::where('tanggal', '>=', $thisMonth)->where('status', 'izin')->count(),
            'tidakHadir' => Absensi::where('tanggal', '>=', $thisMonth)->where('status', 'tidak hadir')->count(),
        ];

        // ── 10 absensi masuk terakhir hari ini (live feed) ──────
        $absensiTerbaru = Absensi::with('siswa.kelas')
            ->whereDate('tanggal', $today)
            ->where('jenis', 'masuk')
            ->orderBy('jam', 'desc')
            ->take(10)
            ->get();

        // ── Pengaturan jam hari ini ──────────────────────────────
        $pengaturanHariIni = Pengaturan::where('tanggal', $today->toDateString())->first();
        if (!$pengaturanHariIni) {
            $pengaturanHariIni = (object) [
                'jam_masuk_awal'  => '05:00',
                'jam_masuk_akhir' => '07:00',
                'jam_pulang'      => '15:00',
            ];
        }

        // ── Status waktu absensi saat ini ───────────────────────
        $now         = Carbon::now();
        $currentTime = $now->format('H:i:s');
        $jamMasukAwal  = $pengaturanHariIni->jam_masuk_awal  ?? '05:00:00';
        $jamMasukAkhir = $pengaturanHariIni->jam_masuk_akhir ?? '07:00:00';
        $jamPulang     = $pengaturanHariIni->jam_pulang       ?? '15:00:00';

        if ($currentTime < $jamMasukAwal) {
            $statusWaktu  = 'Belum waktu masuk';
            $jenisAbsensi = 'masuk';
        } elseif ($currentTime >= $jamMasukAwal && $currentTime <= $jamMasukAkhir) {
            $statusWaktu  = 'Waktu masuk';
            $jenisAbsensi = 'masuk';
        } elseif ($currentTime > $jamMasukAkhir && $currentTime < $jamPulang) {
            $statusWaktu  = 'Terlambat masuk';
            $jenisAbsensi = 'masuk';
        } else {
            $statusWaktu  = 'Waktu pulang';
            $jenisAbsensi = 'pulang';
        }

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalKelas',
            'siswaAktif',
            'siswaPending',
            'absensiHariIni',
            'persentaseHadir',
            'hadirHariIni',
            'absensiHariIniStatus',
            'absensi7Hari',
            'absensiPerKelas',
            'siswaSeringTerlambat',
            'absensiMingguIni',
            'trenKehadiran',
            'rekapBulanIni',
            'absensiTerbaru',
            'pengaturanHariIni',
            'statusWaktu',
            'jenisAbsensi'
        ));
    }
}