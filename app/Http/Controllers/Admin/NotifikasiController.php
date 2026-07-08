<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifikasi = $user->notifications()->paginate(15);
        return view('admin.notifikasi.index', compact('notifikasi'));
    }

    public function markAllRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    // WAJIB DITAMBAHKAN: Fungsi untuk menghapus satu notifikasi
    public function destroy($id)
    {
        $user = Auth::user();
        $notifikasi = $user->notifications()->where('id', $id)->first();

        if ($notifikasi) {
            $notifikasi->delete();
            return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Notifikasi tidak ditemukan.');
    }
}
