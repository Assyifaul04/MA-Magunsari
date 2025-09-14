<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AbsensiExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{

    public function scan()
    {
        return view('admin.absensi.scan');
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

        return view('admin.absensi.masuk', compact('absensi', 'pengaturan'));
    }

    public function keluar()
    {
        $pengaturan = Pengaturan::where('tanggal', Carbon::today()->toDateString())->first();

        $absensi = Absensi::with('siswa')
            ->where('jenis', 'pulang')
            ->whereDate('tanggal', Carbon::today()->toDateString())
            ->orderBy('jam', 'asc')
            ->get();

        return view('admin.absensi.keluar', compact('absensi', 'pengaturan'));
    }

    public function izin()
    {
        $absensi = Absensi::with('siswa')
            ->where('jenis', 'izin')
            ->whereDate('tanggal', Carbon::today()->toDateString())
            ->orderBy('jam', 'asc')
            ->get();

        return view('admin.absensi.izin', compact('absensi'));
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

        return view('admin.absensi.hari_ini', compact('absensi'));
    }

    public function generate()
    {
        $kemarin = Carbon::yesterday()->toDateString();
        $siswaList = Siswa::whereNotNull('rfid')->get();

        foreach ($siswaList as $siswa) {
            $cek = Absensi::where('siswa_id', $siswa->id)
                ->whereDate('tanggal', $kemarin)
                ->exists();

            if (!$cek) {
                Absensi::create([
                    'siswa_id'   => $siswa->id,
                    'jenis'      => null,
                    'status'     => 'tidak hadir',
                    'rfid'       => $siswa->rfid,
                    'keterangan' => 'tidak melakukan absen',
                    'tanggal'    => $kemarin,
                    'jam'        => '00:00:00'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Absensi 'tidak hadir' berhasil ditambahkan untuk siswa yang tidak absen kemarin.",
        ]);
    }


    public function byRange(Request $request)
    {
        $this->generate();

        $tanggalMulai   = $request->input('tanggal_mulai', now()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', now()->toDateString());

        $status = $request->status;
        if ($status === 'tidak_hadir') {
            $status = 'tidak hadir';
        }

        $query = Absensi::with('siswa.kelas')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        if ($request->kelas) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas));
        }

        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->nama) {
            $query->whereHas('siswa', fn($q) => $q->where('nama', 'like', "%{$request->nama}%"));
        }

        if ($status) {
            $query->where('status', $status);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->orderBy('jam', 'asc')->get();

        $totalData = $absensi->count();

        return view('admin.absensi.by_range', compact('absensi', 'tanggalMulai', 'tanggalSelesai', 'totalData'));
    }

    public function export(Request $request)
    {
        return Excel::download(new AbsensiExport($request), 'admin.absensi.xlsx');
    }


    public function print(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?? now()->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?? now()->toDateString();

        $status = $request->status;
        if ($status === 'tidak_hadir') {
            $status = 'tidak hadir';
        }

        $query = Absensi::with('siswa.kelas')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        if ($request->kelas) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas));
        }

        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->nama) {
            $query->whereHas('siswa', fn($q) => $q->where('nama', 'like', "%{$request->nama}%"));
        }

        if ($status) {
            $query->where('status', $status);
        }

        $absensi = $query->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'asc')
            ->get();

        return view('admin.absensi.print', compact('absensi', 'tanggalMulai', 'tanggalSelesai'));
    }

    public function rekapBulanan(Request $request)
    {
        $tahun   = $request->input('tahun');
        $bulan   = $request->input('bulan');
        $kelas   = $request->input('kelas');
        $siswaId = $request->input('siswa');

        $rekap = [];
        $jumlahHari = null;

        // hanya jalan kalau tahun, bulan, kelas, dan siswa dipilih
        if ($tahun && $bulan && $kelas && $siswaId) {
            // Jumlah hari dalam bulan
            $jumlahHari = Carbon::createFromDate($tahun, $bulan)->daysInMonth;

            // Query siswa (hanya siswa yang dipilih)
            $siswaList = Siswa::whereNotNull('rfid')
                ->where('kelas_id', $kelas)
                ->where('id', $siswaId)
                ->with('kelas')
                ->get();

            // Ambil absensi sesuai bulan dan tahun
            $absensi = Absensi::with('siswa')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->get()
                ->groupBy('siswa_id');

            // Susun data per siswa dan per tanggal
            foreach ($siswaList as $siswa) {
                $rekap[$siswa->id] = [
                    'siswa' => $siswa,
                    'data'  => [],
                ];

                for ($hari = 1; $hari <= $jumlahHari; $hari++) {
                    $tanggal = Carbon::createFromDate($tahun, $bulan, $hari)->toDateString();

                    $absenHari = optional($absensi[$siswa->id] ?? collect())
                        ->firstWhere('tanggal', $tanggal);

                    if ($absenHari) {
                        if (in_array($absenHari->status, ['hadir', 'terlambat'])) {
                            $rekap[$siswa->id]['data'][$hari] = '✔';
                        } elseif ($absenHari->status === 'izin') {
                            $rekap[$siswa->id]['data'][$hari] = 'I';
                        } elseif ($absenHari->status === 'sakit') {
                            $rekap[$siswa->id]['data'][$hari] = 'S';
                        } else {
                            $rekap[$siswa->id]['data'][$hari] = '-';
                        }
                    } else {
                        $rekap[$siswa->id]['data'][$hari] = null;
                    }
                }
            }
        }

        $kelasList = Kelas::all();

        return view('admin.absensi.rekap_bulanan', compact(
            'rekap',
            'tahun',
            'bulan',
            'jumlahHari',
            'kelasList',
            'kelas',
            'siswaId'
        ));
    }
}
