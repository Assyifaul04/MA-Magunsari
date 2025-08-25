<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiExport;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{

    public function scan()
    {
        return view('absensi.scan');
    }

    // Halaman scan RFID untuk masuk
    public function masuk()
    {
        $pengaturan = Pengaturan::where('tanggal', Carbon::today()->toDateString())->first();

        $absensi = Absensi::with(['siswa.kelas'])
            ->where('jenis', 'masuk')
            ->whereDate('tanggal', Carbon::today()->toDateString()) // filter hari ini
            ->orderBy('jam', 'asc')
            ->get();

        return view('absensi.masuk', compact('absensi', 'pengaturan'));
    }

    public function keluar()
    {
        $pengaturan = Pengaturan::where('tanggal', Carbon::today()->toDateString())->first();

        $absensi = Absensi::with('siswa')
            ->where('jenis', 'pulang')
            ->whereDate('tanggal', Carbon::today()->toDateString())
            ->orderBy('jam', 'asc')
            ->get();

        return view('absensi.keluar', compact('absensi', 'pengaturan'));
    }

    public function izin()
    {
        $absensi = Absensi::with('siswa')
            ->where('jenis', 'izin')
            ->whereDate('tanggal', Carbon::today()->toDateString())
            ->orderBy('jam', 'asc')
            ->get();

        return view('absensi.izin', compact('absensi'));
    }

    // Proses absensi RFID
    public function store(Request $request)
    {
        $request->validate([
            'rfid' => 'required|string',
            'jenis' => 'nullable|in:masuk,pulang,izin',
            'keterangan' => 'nullable|string',
            'status' => 'nullable|in:hadir,terlambat,pulang,izin,sakit,tidak hadir'
        ]);

        $siswa = Siswa::where('rfid', $request->rfid)->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'RFID tidak ditemukan di data siswa.'
            ], 404);
        }

        $now = Carbon::now();
        $pengaturan = Pengaturan::firstOrCreate(
            ['tanggal' => $now->toDateString()],
            [
                'jam_masuk_awal' => '05:00',
                'jam_masuk_akhir' => '07:00',
                'jam_pulang' => '15:00',
            ]
        );

        $jamMasuk = $pengaturan->jam_masuk_akhir ?? '07:00:00';
        $jamPulang = $pengaturan->jam_pulang ?? '15:00:00';

        $jenis = $request->jenis;

        if (!$jenis) {
            if ($now->format('H:i:s') <= $jamMasuk) {
                $jenis = 'masuk';
            } elseif ($now->format('H:i:s') >= $jamPulang) {
                $jenis = 'pulang';
            } else {
                $jenis = 'masuk';
            }
        }

        $cekAbsensi = Absensi::where('siswa_id', $siswa->id)
            ->where('jenis', $jenis)
            ->whereDate('tanggal', $now->toDateString())
            ->first();

        if ($cekAbsensi) {
            return response()->json([
                'success' => false,
                'message' => "Anda sudah absen {$jenis} hari ini."
            ], 400);
        }

        $status = $request->status;

        if ($jenis === 'masuk') {
            $jamMasukAwal  = $pengaturan->jam_masuk_awal ?? '05:00:00';
            $jamMasukAkhir = $pengaturan->jam_masuk_akhir ?? '07:00:00';

            if ($now->format('H:i:s') < $jamMasukAwal) {
                return response()->json([
                    'success' => false,
                    'message' => "Belum waktunya absen masuk",
                ], 400);
            } elseif ($now->format('H:i:s') >= $jamMasukAwal && $now->format('H:i:s') <= $jamMasukAkhir) {
                $status = 'hadir';
            } else {
                $status = 'terlambat';
            }
        } elseif ($jenis === 'pulang') {
            $status = 'pulang';
        } elseif ($jenis === 'izin') {
            $status = 'izin';
        }



        $absensi = Absensi::create([
            'siswa_id'   => $siswa->id,
            'jenis'      => $jenis,
            'status'     => $status,
            'rfid'       => $request->rfid,
            'keterangan' => $request->keterangan,
            'tanggal'    => $now->toDateString(),
            'jam'        => $now->toTimeString(),
        ]);


        return response()->json([
            'success' => true,
            'message' => "Absensi {$jenis} berhasil dicatat dengan status {$status}.",
            'data' => $absensi->load('siswa.kelas')
        ]);
    }

    public function checkJenis()
    {
        $pengaturan = Pengaturan::firstOrCreate(
            ['tanggal' => Carbon::today()->toDateString()],
            [
                'jam_masuk_awal' => '05:00',
                'jam_masuk_akhir' => '07:00',
                'jam_pulang' => '15:00',
            ]
        );

        $now = Carbon::now();

        $jamMasukAwal  = $pengaturan->jam_masuk_awal ?? '05:00:00';
        $jamMasukAkhir = $pengaturan->jam_masuk_akhir ?? '07:00:00';
        $jamPulang     = $pengaturan->jam_pulang ?? '15:00:00';
        $current       = $now->format('H:i:s');

        if ($current < $jamMasukAwal) {
            $jenis = 'masuk';
            $status = 'belum masuk';
        } elseif ($current >= $jamMasukAwal && $current <= $jamMasukAkhir) {
            $jenis = 'masuk';
            $status = 'hadir';
        } elseif ($current > $jamMasukAkhir && $current < $jamPulang) {
            $jenis = 'masuk';
            $status = 'terlambat';
        } else {
            $jenis = 'pulang';
            $status = 'pulang';
        }

        return response()->json([
            'jenis'          => $jenis,
            'status'         => $status,      // tambahkan status
            'jam_masuk_awal' => $jamMasukAwal,
            'jam_masuk_akhir' => $jamMasukAkhir,
            'jam_pulang'     => $jamPulang,
            'now'            => $current
        ]);
    }


    public function hariIni()
    {
        $today = Carbon::today()->toDateString();

        $absensi = Absensi::with('siswa.kelas')
            ->whereDate('tanggal', $today)
            ->orderBy('jam', 'asc')
            ->get();

        return view('absensi.hari_ini', compact('absensi'));
    }



    public function byRange(Request $request)
    {
        $tanggalMulai   = $request->tanggal_mulai ?? Carbon::today()->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? Carbon::today()->toDateString();
        $hariIni        = Carbon::today()->toDateString();

        $absensi = collect();
        $siswaTidakHadir = collect();
        $siswaBelum = collect();

        // Jika status spesifik hadir/izin/sakit/pulang
        if ($request->status && !in_array($request->status, ['tidak_hadir', 'belum'])) {
            $absensi = Absensi::with('siswa.kelas')
                ->whereDate('tanggal', '>=', $tanggalMulai)
                ->whereDate('tanggal', '<=', $tanggalSelesai)
                ->when($request->kelas, fn($q) => $q->whereHas('siswa.kelas', fn($x) => $x->where('id', $request->kelas)))
                ->when($request->jenis, fn($q) => $q->where('jenis', $request->jenis))
                ->when($request->nama, fn($q) => $q->whereHas('siswa', fn($x) => $x->where('nama', 'like', "%{$request->nama}%")))
                ->where('status', $request->status)
                ->orderBy('tanggal', 'desc')->orderBy('jam', 'asc')
                ->get();
        } elseif ($request->status === 'tidak_hadir') {
            // hanya tampilkan siswa yg tidak hadir
            $siswaTidakHadir = Siswa::with('kelas')
                ->whereNotNull('rfid')
                ->when($request->kelas, fn($q) => $q->where('kelas_id', $request->kelas))
                ->when($request->nama, fn($q) => $q->where('nama', 'like', "%{$request->nama}%"))
                ->whereDoesntHave('absensi', function ($q) use ($tanggalMulai, $tanggalSelesai) {
                    $q->whereBetween(DB::raw('DATE(tanggal)'), [$tanggalMulai, $tanggalSelesai])
                        ->whereIn('status', ['hadir', 'terlambat', 'izin', 'sakit', 'pulang']);
                })
                ->get();
        } elseif ($request->status === 'belum') {
            // hanya tampilkan siswa yg belum absen (khusus hari ini)
            $siswaBelum = Siswa::with('kelas')
                ->whereNotNull('rfid')
                ->when($request->kelas, fn($q) => $q->where('kelas_id', $request->kelas))
                ->when($request->nama, fn($q) => $q->where('nama', 'like', "%{$request->nama}%"))
                ->whereDoesntHave('absensi', function ($q) use ($hariIni) {
                    $q->whereDate('tanggal', $hariIni);
                })
                ->get();
        } else {
            // tanpa filter status -> ambil absensi + list tambahan
            $absensi = Absensi::with('siswa.kelas')
                ->whereDate('tanggal', '>=', $tanggalMulai)
                ->whereDate('tanggal', '<=', $tanggalSelesai)
                ->when($request->kelas, fn($q) => $q->whereHas('siswa.kelas', fn($x) => $x->where('id', $request->kelas)))
                ->when($request->jenis, fn($q) => $q->where('jenis', $request->jenis))
                ->when($request->nama, fn($q) => $q->whereHas('siswa', fn($x) => $x->where('nama', 'like', "%{$request->nama}%")))
                ->orderBy('tanggal', 'desc')->orderBy('jam', 'asc')
                ->get();

            // Tambahan logika: kalau filter 1 hari
            if ($tanggalMulai == $tanggalSelesai) {
                if ($tanggalMulai < $hariIni) {
                    // lampau -> tidak hadir
                    $siswaTidakHadir = Siswa::with('kelas')
                        ->whereNotNull('rfid')
                        ->when($request->kelas, fn($q) => $q->where('kelas_id', $request->kelas))
                        ->when($request->nama, fn($q) => $q->where('nama', 'like', "%{$request->nama}%"))
                        ->whereDoesntHave('absensi', function ($q) use ($tanggalMulai) {
                            $q->whereDate('tanggal', $tanggalMulai)
                                ->whereIn('status', ['hadir', 'terlambat', 'izin', 'sakit', 'pulang']);
                        })
                        ->get();
                } elseif ($tanggalMulai == $hariIni) {
                    // hari ini -> belum
                    $siswaBelum = Siswa::with('kelas')
                        ->whereNotNull('rfid')
                        ->when($request->kelas, fn($q) => $q->where('kelas_id', $request->kelas))
                        ->when($request->nama, fn($q) => $q->where('nama', 'like', "%{$request->nama}%"))
                        ->whereDoesntHave('absensi', function ($q) use ($hariIni) {
                            $q->whereDate('tanggal', $hariIni);
                        })
                        ->get();
                }
            }
        }

        return view('absensi.by_range', compact('absensi', 'siswaTidakHadir', 'siswaBelum'));
    }



    public function export(Request $request)
    {
        return Excel::download(new AbsensiExport($request), 'absensi.xlsx');
    }


    public function print(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?? now()->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->toDateString();

        $query = Absensi::with('siswa.kelas')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        if ($request->kelas) {
            $query->whereHas('siswa.kelas', fn($q) => $q->where('id', $request->kelas));
        }
        if ($request->jenis) $query->where('jenis', $request->jenis);
        if ($request->nama) {
            $query->whereHas('siswa', fn($q) => $q->where('nama', 'like', "%{$request->nama}%"));
        }
        if ($request->status && $request->status != 'tidak_hadir') {
            $query->where('status', $request->status);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->orderBy('jam', 'asc')->get();

        $siswaTidakHadir = collect();
        if ($request->status == 'tidak_hadir' || !$request->status) {
            $siswaTidakHadir = Siswa::with('kelas')
                ->whereNotNull('rfid')
                ->when($request->kelas, fn($q) => $q->where('kelas_id', $request->kelas))
                ->when($request->nama, fn($q) => $q->where('nama', 'like', "%{$request->nama}%"))
                ->whereDoesntHave('absensi', function ($q) use ($tanggalMulai, $tanggalSelesai) {
                    $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                        ->whereIn('status', ['hadir', 'terlambat', 'izin', 'sakit', 'pulang']);
                })
                ->get();
        }

        return view('absensi.print', compact('absensi', 'siswaTidakHadir', 'tanggalMulai', 'tanggalSelesai'));
    }


    public function performa(Request $request)
    {
        $query = Siswa::with(['kelas', 'absensi' => function ($q) {
            $q->where('jenis', 'masuk');
        }]);

        if ($request->nama) {
            $query->where('nama', 'like', "%{$request->nama}%");
        }

        if ($request->kelas) {
            $query->where('kelas_id', $request->kelas);
        }

        $siswaList = $query->get();

        $data = $siswaList->map(function ($siswa) {
            $totalMasuk = $siswa->absensi->count();
            $tepatWaktu = $siswa->absensi->where('status', 'hadir')->count();
            $terlambat  = $siswa->absensi->where('status', 'terlambat')->count();

            $performa = $tepatWaktu > $terlambat ? 'Rajin' : 'Malas';

            return [
                'nama'      => $siswa->nama,
                'kelas'     => $siswa->kelas->nama ?? '-',
                'totalMasuk' => $totalMasuk,
                'tepatWaktu' => $tepatWaktu,
                'terlambat' => $terlambat,
                'performa'  => $performa
            ];
        });

        // filter performa
        if ($request->performa) {
            $data = $data->filter(function ($d) use ($request) {
                return strtolower($d['performa']) === strtolower($request->performa);
            });
        }

        return view('absensi.performa', [
            'data' => $data,
            'kelasList' => Kelas::all()
        ]);
    }
}
