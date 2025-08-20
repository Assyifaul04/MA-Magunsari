<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaturan;
use App\Models\Absensi;
use Carbon\Carbon;

class PengaturanController extends Controller
{
    public function edit()
    {
        $pengaturan = Pengaturan::first();
        $sudahAdaMasuk = Absensi::where('jenis', 'masuk')
            ->whereDate('tanggal', Carbon::today())
            ->exists();

        if (!$pengaturan) {
            $pengaturan = new Pengaturan([
                'jam_masuk' => '07:00',
                'jam_pulang' => '15:00',
            ]);
        }

        return view('master.pengaturan.edit', compact('pengaturan', 'sudahAdaMasuk'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'jam_masuk_awal' => 'nullable|date_format:H:i',
            'jam_masuk_akhir' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
        ]);
    
        $pengaturan = Pengaturan::first();
        $data = $request->all();
    
        $data['jam_masuk_awal'] = $data['jam_masuk_awal'] ?: '05:00';
        $data['jam_masuk_akhir'] = $data['jam_masuk_akhir'] ?: '07:00';
        $data['jam_pulang'] = $data['jam_pulang'] ?: '15:00';
    
        $sudahAdaMasuk = Absensi::where('jenis', 'masuk')
            ->whereDate('tanggal', Carbon::today())
            ->exists();
    
        if ($sudahAdaMasuk) {
            unset($data['jam_masuk_awal'], $data['jam_masuk_akhir']); // terkunci
        }
    
        if (!$pengaturan) {
            $pengaturan = Pengaturan::create($data);
        } else {
            $pengaturan->update($data);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil diperbarui.',
            'jam_masuk_locked' => $sudahAdaMasuk,
            'data' => $pengaturan
        ]);
    }
    

    // Tambahkan route untuk live check jam masuk
    public function checkJamMasuk()
    {
        $sudahAdaMasuk = Absensi::where('jenis', 'masuk')
            ->whereDate('tanggal', Carbon::today())
            ->exists();

        return response()->json([
            'jam_masuk_locked' => $sudahAdaMasuk
        ]);
    }
}
