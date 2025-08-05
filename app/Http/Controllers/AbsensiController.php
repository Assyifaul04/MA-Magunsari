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
        $tanggal = $request->input('tanggal') ?? date('Y-m-d');
        $nama = $request->input('nama');
        $keterangan = $request->input('keterangan');
        $masuk_from = $request->input('masuk_from');
        $masuk_to = $request->input('masuk_to');
        $pulang_from = $request->input('pulang_from');
        $pulang_to = $request->input('pulang_to');

        // Ambil absensi sesuai filter
        $absensisQuery = Absensi::with('siswa')
            ->where('tanggal', $tanggal);

        if ($nama) {
            $absensisQuery->whereHas('siswa', function ($q) use ($nama) {
                $q->where('nama', 'like', "%{$nama}%");
            });
        }

        if ($keterangan) {
            $absensisQuery->where('keterangan', $keterangan);
        }

        if ($masuk_from) {
            $absensisQuery->whereTime('waktu_masuk', '>=', $masuk_from);
        }
        if ($masuk_to) {
            $absensisQuery->whereTime('waktu_masuk', '<=', $masuk_to);
        }

        if ($pulang_from) {
            $absensisQuery->whereTime('waktu_pulang', '>=', $pulang_from);
        }
        if ($pulang_to) {
            $absensisQuery->whereTime('waktu_pulang', '<=', $pulang_to);
        }

        $absensis = $absensisQuery->orderByRaw("CASE WHEN waktu_masuk IS NULL THEN 1 ELSE 0 END")
            ->orderBy('waktu_masuk')
            ->get();

        // Siswa tanpa absensi hari ini (untuk ditampilkan sebagai belum)
        $absenIds = $absensis->pluck('siswa_id')->toArray();
        $siswas = Siswa::whereNotIn('id', $absenIds)
            ->when($nama, function ($q) use ($nama) {
                $q->where('nama', 'like', "%{$nama}%");
            })
            ->get();

        return view('admin.absensi.index', compact(
            'absensis',
            'siswas',
            'tanggal',
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
    
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'RFID tidak terdaftar'], 404);
        }
    
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
    
        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $today)
            ->first();
    
        if (!$absensi) {
            $keterangan = $now->hour >= 7 ? 'terlambat' : 'hadir';
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
                'absensi' => $this->formatAbsensiForResponse($absensi)
            ]);
        } else {
            if (!$absensi->waktu_pulang) {
                $absensi->update([
                    'waktu_pulang' => $now->format('H:i:s')
                ]);
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'Absen pulang dicatat',
                    'absensi' => $this->formatAbsensiForResponse($absensi->refresh())
                ]);
            } else {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Kamu sudah absen masuk dan pulang hari ini',
                    'absensi' => $this->formatAbsensiForResponse($absensi)
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
}
