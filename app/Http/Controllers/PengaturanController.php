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
        $now = Carbon::now();
        $today = $now->toDateString();
        $jamSekarang = $now->format('H:i');

        $pengaturan = Pengaturan::where('tanggal', $today)->first();

        if (!$pengaturan && $jamSekarang >= '05:00') {
            $pengaturan = Pengaturan::create([
                'tanggal' => $today,
                'jam_masuk_awal' => '05:00',
                'jam_masuk_akhir' => '07:00',
                'jam_pulang' => '15:00',
            ]);
        }

        $sudahAdaMasuk = Absensi::where('jenis', 'masuk')
            ->whereDate('tanggal', $today)
            ->exists();

        return view('master.pengaturan.edit', compact('pengaturan', 'sudahAdaMasuk'));
    }

    public function update(Request $request)
    {
        $today = Carbon::today()->toDateString();

        $request->validate([
            'jam_masuk_awal' => 'nullable|date_format:H:i',
            'jam_masuk_akhir' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
        ]);

        $sudahAdaMasuk = Absensi::where('jenis', 'masuk')
            ->whereDate('tanggal', $today)
            ->exists();

        $data = [
            'tanggal' => $today,
            'jam_masuk_awal' => $request->jam_masuk_awal ?? '05:00',
            'jam_masuk_akhir' => $request->jam_masuk_akhir ?? '07:00',
            'jam_pulang' => $request->jam_pulang ?? '15:00',
        ];

        if ($sudahAdaMasuk) {
            unset($data['jam_masuk_awal'], $data['jam_masuk_akhir']);
        }

        Pengaturan::updateOrCreate(['tanggal' => $today], $data);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil diperbarui.',
            'jam_masuk_locked' => $sudahAdaMasuk,
            'data' => $data
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
