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
    public function index()
    {
        // Ambil data guru yang login beserta kelasnya
        $guru = Guru::with('kelas')->where('user_id', Auth::id())->first();

        if (!$guru || $guru->kelas->isEmpty()) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda belum ditugaskan menjadi Wali Kelas.');
        }

        // Ambil ID semua kelas yang diampu oleh guru ini (bisa 1 atau 2 kelas)
        $kelasIds = $guru->kelas->pluck('id');

        // Ambil data siswa yang berada di kelas-kelas tersebut
        $siswas = Siswa::with('kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->orderBy('nama', 'asc')
            ->get();

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
            'kelas' => $siswa->kelas->nama,
            'absensi' => $riwayatAbsensi
        ]);
    }
}
