<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;

class AbsensiController extends Controller
{
    // Halaman absensi harian dengan filter dinamis
    public function index(Request $request)
    {
        $tanggalFilter = $request->input('tanggal', 'hari_ini'); // default ke hari ini
        $kelas = $request->input('kelas');
        $nama = $request->input('nama');
        $keterangan = $request->input('keterangan');
        $masuk_from = $request->input('masuk_from');
        $masuk_to = $request->input('masuk_to');
        $pulang_from = $request->input('pulang_from');
        $pulang_to = $request->input('pulang_to');

        // Tangani filter tanggal
        switch ($tanggalFilter) {
            case 'kemarin':
                $tanggalMulai = now()->subDay()->toDateString();
                $tanggalAkhir = $tanggalMulai;
                break;
            case '7_hari':
                $tanggalMulai = now()->subDays(6)->toDateString();
                $tanggalAkhir = now()->toDateString();
                break;
            case '1_bulan':
                $tanggalMulai = now()->subDays(30)->toDateString();
                $tanggalAkhir = now()->toDateString();
                break;
            case 'hari_ini':
            default:
                $tanggalMulai = now()->toDateString();
                $tanggalAkhir = $tanggalMulai;
                break;
        }

        // Ambil absensi sesuai filter
        $absensisQuery = Absensi::with('siswa')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);

        if ($nama) {
            $absensisQuery->whereHas('siswa', function ($q) use ($nama) {
                $q->where('nama', 'like', "%{$nama}%");
            });
        }

        if ($kelas) {
            $absensisQuery->whereHas('siswa', function ($q) use ($kelas) {
                $q->where('kelas', $kelas);
            });
        }

        if ($keterangan) {
            $absensisQuery->where('keterangan', $keterangan);
        }

        $absensis = $absensisQuery
            ->orderByRaw("CASE WHEN waktu_masuk IS NULL THEN 1 ELSE 0 END")
            ->orderBy('waktu_masuk')
            ->get();

        // Ambil ID siswa yang sudah absen
        $absenIds = $absensis->pluck('siswa_id')->unique()->toArray();

        // Siswa yang belum absen
        $siswasQuery = Siswa::whereNotIn('id', $absenIds);

        if ($nama) {
            $siswasQuery->where('nama', 'like', "%{$nama}%");
        }

        if ($kelas) {
            $siswasQuery->where('kelas', $kelas);
        }

        $siswas = $siswasQuery->get();

        $tanggal = $tanggalMulai;

        return view('admin.absensi.index', compact(
            'absensis',
            'siswas',
            'tanggalFilter',
            'tanggal',
            'kelas',
            'nama',
            'keterangan',
            'masuk_from',
            'masuk_to',
            'pulang_from',
            'pulang_to'
        ));
    }


    // Rekap absensi per siswa dalam rentang
    public function rekap(Request $request)
    {
        $start = $request->input('start') ?? date('Y-m-01');
        $end = $request->input('end') ?? date('Y-m-d');

        $rekap = Siswa::with(['absensi' => function ($q) use ($start, $end) {
            $q->whereBetween('tanggal', [$start, $end]);
        }])->get();

        return view('admin.absensi.rekap', compact('rekap', 'start', 'end'));
    }

    // Export Excel (mengikutsertakan filter tanggal mulai/akhir)
    public function export(Request $request)
    {
        $start = $request->input('start') ?? date('Y-m-01');
        $end = $request->input('end') ?? date('Y-m-d');

        return Excel::download(new AbsensiExport($start, $end), 'rekap_absensi.xlsx');
    }

    // API scan RFID (masuk / pulang)
    public function scanRfid(Request $request)
    {
        $rfid = $request->input('rfid_uid');
        $siswa = Siswa::where('rfid_uid', $rfid)->first();
        $tanggal = $request->input('tanggal');
        $masuk_from = $request->input('masuk_from');
        $masuk_to = $request->input('masuk_to');
        $pulang_from = $request->input('pulang_from');
        $pulang_to = $request->input('pulang_to');

        if (!$tanggal || (!$masuk_from && !$masuk_to && !$pulang_from && !$pulang_to)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Harap terapkan filter tanggal dan jam masuk/pulang sebelum melakukan absensi.'
            ], 400);
        }

        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'RFID tidak terdaftar'], 404);
        }

        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $today)
            ->first();

        if (!$absensi) {
            $waktuMasukTepat = Carbon::createFromTimeString('07:00:00', 'Asia/Jakarta');
            $keterangan = $now->greaterThan($waktuMasukTepat) ? 'terlambat' : 'hadir';
            $absensi = Absensi::create([
                'id' => Str::uuid(),
                'siswa_id' => $siswa->id,
                'tanggal' => $today,
                'waktu_masuk' => $now->format('H:i:s'),
                'keterangan' => $keterangan
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Absen masuk dicatat',
                'filter_message' => $this->buildFilterMessage($masuk_from, $masuk_to, $pulang_from, $pulang_to),
                'absensi' => $this->formatAbsensiForResponse($absensi)
            ]);
        } else {
            if (!$absensi->waktu_pulang) {
                $jamPulangMin = $pulang_from ? Carbon::createFromTimeString($pulang_from, 'Asia/Jakarta') : Carbon::createFromTime(13, 0, 0, 'Asia/Jakarta');
                $jamPulangMax = $pulang_to ? Carbon::createFromTimeString($pulang_to, 'Asia/Jakarta') : Carbon::createFromTime(15, 0, 0, 'Asia/Jakarta');

                if ($now->lessThan($jamPulangMin) || $now->greaterThan($jamPulangMax)) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Absen pulang hanya diperbolehkan antara jam ' . $jamPulangMin->format('H:i') . ' - ' . $jamPulangMax->format('H:i'),
                        'absensi' => $this->formatAbsensiForResponse($absensi)
                    ], 400);
                }

                $absensi->update([
                    'waktu_pulang' => $now->format('H:i:s')
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Absen pulang dicatat',
                    'filter_message' => $this->buildFilterMessage($masuk_from, $masuk_to, $pulang_from, $pulang_to),
                    'absensi' => $this->formatAbsensiForResponse($absensi->refresh())
                ]);
            }
        }
    }



    // Lookup untuk frontend kecil (by RFID + tanggal)
    public function lookup(Request $request)
    {
        $rfid = $request->input('rfid_uid');
        $tanggal = $request->input('tanggal') ?? date('Y-m-d');
        $siswa = Siswa::where('rfid_uid', $rfid)->first();
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'RFID tidak terdaftar'], 404);
        }

        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $tanggal)
            ->first();

        if (!$absensi) {
            return response()->json(['success' => true, 'absensi' => null]);
        }

        return response()->json([
            'success' => true,
            'absensi' => $this->formatAbsensiForResponse($absensi),
        ]);
    }

    // Helper formatting
    protected function formatAbsensiForResponse(Absensi $absensi)
    {
        return [
            'siswa_id' => $absensi->siswa_id,
            'waktu_masuk' => $absensi->waktu_masuk,
            'waktu_pulang' => $absensi->waktu_pulang,
            'keterangan' => $absensi->keterangan,
        ];
    }

    protected function buildFilterMessage($masuk_from, $masuk_to, $pulang_from, $pulang_to)
    {
        $messages = [];

        if ($masuk_from || $masuk_to) {
            $jamMasuk = '';
            if ($masuk_from) $jamMasuk .= 'Dari ' . $masuk_from;
            if ($masuk_from && $masuk_to) $jamMasuk .= ' - ';
            if ($masuk_to) $jamMasuk .= 'Sampai ' . $masuk_to;

            $messages[] = "<li><strong>Jam Masuk:</strong> {$jamMasuk}</li>";
        }

        if ($pulang_from || $pulang_to) {
            $jamPulang = '';
            if ($pulang_from) $jamPulang .= 'Dari ' . $pulang_from;
            if ($pulang_from && $pulang_to) $jamPulang .= ' - ';
            if ($pulang_to) $jamPulang .= 'Sampai ' . $pulang_to;

            $messages[] = "<li><strong>Jam Pulang:</strong> {$jamPulang}</li>";
        }

        if (empty($messages)) return null;

        return '<ul class="mb-0">' . implode('', $messages) . '</ul>';
    }
}
