<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class LaporanRfidAdminController extends Controller
{
    /**
     * Menampilkan daftar siswa KHAS dari id siswa yang dikirim via notifikasi guru
     */
    public function index()
    {
        // 1. Ambil dari 'notifications' (SEMUA notifikasi, baik yang sudah dibaca maupun belum).
        // Karena berdasarkan RfidHilangNotification, key-nya adalah 'siswa_id'.
        $siswaIdsDariNotif = auth()->user()->notifications
            ->pluck('data.siswa_id') 
            ->filter()               
            ->unique()               
            ->toArray();

        // 2. Jika tabel notifikasi kosong/tidak ada id siswa, paksa tabel menjadi kosong
        if (empty($siswaIdsDariNotif)) {
            $siswas = collect(); 
        } else {
            // 3. Ambil data siswa SESUAI ID dari notifikasi
            $siswas = Siswa::with('kelas')
                ->whereIn('id', $siswaIdsDariNotif)
                // WAJIB ditambah where('status', 'pending') agar jika siswa sudah di-scan RFID barunya 
                // (statusnya jadi 'aktif' di SiswaController), dia otomatis hilang dari tabel laporan ini.
                ->where('status', 'pending') 
                ->orderBy('nama', 'asc')
                ->get();
        }

        return view('admin.rfid.laporan_hilang', compact('siswas'));
    }

    /**
     * Tandai notifikasi sebagai "dibaca" lalu redirect ke halaman Laporan Hilang
     */
    public function readNotification($notifId)
    {
        $notification = auth()->user()->notifications()->find($notifId);
        
        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->route('rfid.laporan-hilang');
    }
}