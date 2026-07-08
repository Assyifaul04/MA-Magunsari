<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Siswa;

class SiswaGuruController extends Controller
{
    /**
     * Menampilkan daftar siswa di kelas yang diampu oleh guru ini.
     */
    public function index(Request $request)
    {
        // Ambil data guru yang login beserta kelasnya
        $guru = Guru::with('kelas')->where('user_id', Auth::id())->first();

        if (!$guru || $guru->kelas->isEmpty()) {
            return redirect()->route('guru.dashboard')
                             ->with('error', 'Anda belum ditugaskan menjadi Wali Kelas.');
        }

        // Ambil ID semua kelas yang diampu oleh guru ini
        $kelasIds = $guru->kelas->pluck('id');
        $filterKelasId = $request->query('kelas_id');

        $query = Siswa::with('kelas')->orderBy('nama', 'asc');

        // Jika kelas_id diklik dari sidebar, filter ke kelas itu saja
        if ($filterKelasId && $kelasIds->contains($filterKelasId)) {
            $query->where('kelas_id', $filterKelasId);
        } else {
            $query->whereIn('kelas_id', $kelasIds);
        }

        $siswas = $query->get();

        return view('guru.siswa.index', compact('guru', 'siswas'));
    }

    /**
     * Menampilkan detail data siswa beserta riwayat kartu RFID nya.
     */
    public function show(Siswa $siswa)
    {
        // Validasi keamanan: Pastikan guru hanya bisa melihat siswa di kelasnya sendiri
        $guru = Guru::where('user_id', Auth::id())->first();
        $kelasIds = $guru->kelas->pluck('id')->toArray();

        if (!in_array($siswa->kelas_id, $kelasIds)) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        // Ambil riwayat absensi beserta data siswa
        $riwayatAbsensi = $siswa->absensi()->orderBy('tanggal', 'desc')->take(30)->get();

        // Kembalikan data dalam bentuk JSON untuk dibaca oleh AJAX/Javascript di modal
        return response()->json([
            'siswa' => $siswa,
            'kelas' => $siswa->kelas->nama, // Sudah menggunakan properti 'nama' yang benar
            'absensi' => $riwayatAbsensi
        ]);
    }
}