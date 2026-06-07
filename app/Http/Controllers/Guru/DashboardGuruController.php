<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Absensi;
use Carbon\Carbon;

class DashboardGuruController extends Controller
{
    public function index()
    {
        $guru = Guru::with([
                'kelas.siswa.absensi' => function ($q) {
                    // Eager load absensi 2 bulan terakhir agar cukup untuk semua perhitungan
                    $q->where('tanggal', '>=', now()->subMonth()->startOfMonth()->toDateString());
                },
                'kelas.siswa.kelas',
            ])
            ->where('user_id', Auth::id())
            ->first();

        if (!$guru) {
            return view('guru.dashboard')->with([
                'error'      => 'Data profil guru Anda tidak ditemukan.',
                'daftarKelas'=> collect(),
            ]);
        }

        $daftarKelas = $guru->kelas;

        /* ── Kumpulkan semua siswa di kelas yang diampu ── */
        $semuaSiswa = $daftarKelas->flatMap(fn($k) => $k->siswa);
        $totalSiswa = $semuaSiswa->count();
        $today      = today()->toDateString();

        /* ── Statistik hari ini ── */
        $absensiHariIniAll = $semuaSiswa->flatMap(fn($s) =>
            $s->absensi->where('tanggal', $today)->where('jenis', 'masuk')
        );

        $hadirHariIni     = $absensiHariIniAll->whereIn('status', ['hadir', 'terlambat'])->count();
        $terlambatHariIni = $absensiHariIniAll->where('status', 'terlambat')->count();
        $izinHariIni      = $absensiHariIniAll->where('status', 'izin')->count();
        $alphaHariIni     = $absensiHariIniAll->whereIn('status', ['alpha', 'tidak hadir'])->count();
        $persentaseHadir  = $totalSiswa > 0 ? round($hadirHariIni / $totalSiswa * 100) : 0;

        /* ── Status untuk donut chart ── */
        $statusHariIni = [
            'hadir'     => $absensiHariIniAll->where('status', 'hadir')->count(),
            'terlambat' => $terlambatHariIni,
            'izin'      => $izinHariIni,
            'alpha'     => $alphaHariIni,
        ];

        /* ── Rekap bulan ini ── */
        $startBulan = now()->startOfMonth()->toDateString();
        $absensiBulanAll = $semuaSiswa->flatMap(fn($s) =>
            $s->absensi->where('tanggal', '>=', $startBulan)->where('jenis', 'masuk')
        );
        $rekapBulan = [
            'hadir'     => $absensiBulanAll->where('status', 'hadir')->count(),
            'terlambat' => $absensiBulanAll->where('status', 'terlambat')->count(),
            'izin'      => $absensiBulanAll->where('status', 'izin')->count(),
            'alpha'     => $absensiBulanAll->whereIn('status', ['alpha','tidak hadir'])->count(),
        ];

        /* ── Tren 7 hari terakhir ── */
        $absensi7Hari = collect();
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->toDateString();
            $dayAbsensi = $semuaSiswa->flatMap(fn($s) =>
                $s->absensi->where('tanggal', $tgl)->where('jenis', 'masuk')
            );
            $absensi7Hari->push([
                'tanggal'   => now()->subDays($i)->isoFormat('D MMM'),
                'hadir'     => $dayAbsensi->where('status', 'hadir')->count(),
                'terlambat' => $dayAbsensi->where('status', 'terlambat')->count(),
                'izin'      => $dayAbsensi->where('status', 'izin')->count(),
                'alpha'     => $dayAbsensi->whereIn('status', ['alpha','tidak hadir'])->count(),
            ]);
        }

        /* ── Data chart perbandingan per kelas (hari ini) ── */
        $kelasChartData = $daftarKelas->map(function ($kelas) use ($today) {
            $absensi = $kelas->siswa->flatMap(fn($s) =>
                $s->absensi->where('tanggal', $today)->where('jenis', 'masuk')
            );
            return [
                'nama'      => $kelas->nama,
                'hadir'     => $absensi->where('status', 'hadir')->count(),
                'terlambat' => $absensi->where('status', 'terlambat')->count(),
                'izin'      => $absensi->where('status', 'izin')->count(),
                'alpha'     => $absensi->whereIn('status', ['alpha','tidak hadir'])->count(),
            ];
        })->values();

        /* ── Siswa paling sering terlambat bulan ini ── */
        $siswaSeringTerlambat = $semuaSiswa->map(function ($siswa) use ($startBulan) {
            $siswa->terlambat_count = $siswa->absensi
                ->where('tanggal', '>=', $startBulan)
                ->where('jenis', 'masuk')
                ->where('status', 'terlambat')
                ->count();
            return $siswa;
        })
        ->filter(fn($s) => $s->terlambat_count > 0)
        ->sortByDesc('terlambat_count')
        ->take(5)
        ->values();

        /* ── Absensi terbaru (8 terakhir hari ini, semua kelas) ── */
        $siswaIds = $semuaSiswa->pluck('id');
        $absensiTerbaru = Absensi::with(['siswa.kelas'])
            ->whereIn('siswa_id', $siswaIds)
            ->where('tanggal', $today)
            ->where('jenis', 'masuk')
            ->orderByDesc('jam')
            ->limit(8)
            ->get();

        /* ── Status waktu (opsional, gunakan jika ada model Pengaturan) ── */
        $statusWaktu = 'Aktif';

        return view('guru.dashboard', compact(
            'guru',
            'daftarKelas',
            'totalSiswa',
            'hadirHariIni',
            'terlambatHariIni',
            'izinHariIni',
            'alphaHariIni',
            'persentaseHadir',
            'statusHariIni',
            'rekapBulan',
            'absensi7Hari',
            'kelasChartData',
            'siswaSeringTerlambat',
            'absensiTerbaru',
            'statusWaktu'
        ));
    }
}