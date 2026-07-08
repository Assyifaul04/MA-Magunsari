<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use App\Notifications\RfidHilangNotification;
use Illuminate\Support\Facades\Notification;

class RfidGuruController extends Controller
{
    /**
     * Menampilkan daftar siswa wali kelas yang belum memiliki kartu RFID.
     */
    public function belumTerdaftar(Request $request)
    {
        $guru = Guru::with('kelas')->where('user_id', Auth::id())->first();

        if (!$guru || $guru->kelas->isEmpty()) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda belum ditugaskan menjadi Wali Kelas.');
        }

        $kelasIds = $guru->kelas->pluck('id');

        $siswas = Siswa::with('kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->where('status', '!=', 'non_aktif')
            ->where(function ($query) {
                $query->whereNull('rfid')->orWhere('rfid', '');
            })
            ->orderBy('nama', 'asc')
            ->get();

        return view('guru.rfid.belum_terdaftar', compact('siswas'));
    }

    /**
     * Menampilkan daftar siswa yang MEMILIKI RFID untuk bisa dilaporkan hilang.
     */
    public function laporanHilang(Request $request)
    {
        $guru = Guru::with('kelas')->where('user_id', Auth::id())->first();

        if (!$guru || $guru->kelas->isEmpty()) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda belum ditugaskan menjadi Wali Kelas.');
        }

        $kelasIds = $guru->kelas->pluck('id');

        // Filter siswa yang SUDAH memiliki kartu RFID
        $siswas = Siswa::with('kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->where('status', '!=', 'non_aktif') // <-- TAMBAHKAN FILTER INI
            ->whereNotNull('rfid')
            ->where('rfid', '!=', '')
            ->orderBy('nama', 'asc')
            ->get();

        return view('guru.rfid.laporan_hilang', compact('siswas'));
    }

    /**
     * Proses mereset/melaporkan RFID hilang
     */
    public function submitLaporanHilang(Request $request, $id)
    {
        $guru = Guru::with('kelas')->where('user_id', Auth::id())->first();
        $kelasIds = $guru->kelas->pluck('id')->toArray();

        $siswa = Siswa::findOrFail($id);

        if (!in_array($siswa->kelas_id, $kelasIds)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk siswa ini.');
        }

        $siswa->rfid = null;
        $siswa->status = 'pending';
        $siswa->save();

        // 2. Kirim Notifikasi ke Admin & Super Admin
        $admins = \App\Models\User::whereIn('role', ['admin', 'superAdmin'])->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\RfidHilangNotification($siswa));

        return redirect()->back()->with('success', "Kartu RFID atas nama {$siswa->nama} berhasil dinonaktifkan.");
    }
}