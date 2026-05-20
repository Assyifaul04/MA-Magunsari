<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotifikasiWhatsapp;
use Illuminate\Http\Request;

class NotifikasiWhatsappController extends Controller
{
    public function index()
    {
        // Eager load relasi untuk mencegah N+1 query
        $notifikasis = NotifikasiWhatsapp::with([
            'siswa.kelas',
            'orangTua',
            'absensi'
        ])->latest()->paginate(20);

        return view('admin.notifikasiwa.index', compact('notifikasis'));
    }

    public function show(NotifikasiWhatsapp $notifikasi)
    {
        $notifikasi->load(['siswa.kelas', 'orangTua', 'absensi']);

        $notifikasi->response_gateway_decoded = json_decode($notifikasi->response_gateway);

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    public function destroy(NotifikasiWhatsapp $notifikasi)
    {
        $notifikasi->delete();

        return back()->with('success', 'Log notifikasi berhasil dihapus');
    }
}