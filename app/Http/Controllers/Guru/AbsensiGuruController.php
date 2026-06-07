<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Absensi;
use Carbon\Carbon;

class AbsensiGuruController extends Controller
{
    /**
     * Live Monitoring absensi siswa hari ini.
     */
    public function hariIni()
    {
        $guru = Guru::with('kelas')->where('user_id', Auth::id())->first();

        if (!$guru || $guru->kelas->isEmpty()) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda belum ditugaskan menjadi Wali Kelas.');
        }

        $kelasIds = $guru->kelas->pluck('id');
        $hariIni = Carbon::today()->toDateString();

        $siswas = Siswa::whereIn('kelas_id', $kelasIds)->orderBy('nama', 'asc')->get();

        $absensiHariIni = Absensi::where('tanggal', $hariIni)
            ->whereIn('siswa_id', $siswas->pluck('id'))
            ->get()
            ->keyBy('siswa_id'); 

        $stat = [
            'total'     => $siswas->count(),
            'hadir'     => $absensiHariIni->where('status', 'hadir')->count(),
            'terlambat' => $absensiHariIni->where('status', 'terlambat')->count(),
            'sakit'     => $absensiHariIni->where('status', 'sakit')->count(),
            'izin'      => $absensiHariIni->where('status', 'izin')->count(),
            'alfa'      => $siswas->count() - $absensiHariIni->count() // Yang belum tap/belum diinput
        ];

        return view('guru.absensi.hari_ini', compact('guru', 'siswas', 'absensiHariIni', 'stat'));
    }

    /**
     * Input atau edit absensi manual oleh Guru (Override system jika kartu ketinggalan/sakit/izin).
     */
    public function updateManual(Request $request, $siswaId)
    {
        $request->validate([
            'status'     => 'required|in:hadir,sakit,izin,alfa,terlambat',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $siswa = Siswa::findOrFail($siswaId);
        $hariIni = Carbon::today()->toDateString();

        // Gunakan updateOrCreate: jika data hari ini belum ada maka buat baru, jika sudah ada maka timpa kodenya
        Absensi::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'tanggal'  => $hariIni,
            ],
            [
                'status'     => $request->status,
                'jam_masuk'  => $request->status == 'hadir' || $request->status == 'terlambat' ? Carbon::now()->toTimeString() : null,
                'metode'     => 'manual', // Menandakan diinput manual oleh guru, bukan mesin RFID
                'keterangan' => $request->keterangan ?? 'Diinput oleh Wali Kelas'
            ]
        );

        return redirect()->back()->with('success', 'Status kehadiran ' . $siswa->nama . ' berhasil diperbarui.');
    }

    /**
     * Melihat rekap bulanan absensi kelas.
     */
    public function rekap(Request $request)
    {
        $guru = Guru::with('kelas')->where('user_id', Auth::id())->first();
        $kelasIds = $guru->kelas->pluck('id');

        // Mengambil bulan dan tahun filter (default bulan & tahun sekarang)
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        $siswas = Siswa::whereIn('kelas_id', $kelasIds)
            ->with(['absensi' => function($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            }])
            ->orderBy('nama', 'asc')
            ->get();

        return view('guru.absensi.rekap', compact('guru', 'siswas', 'bulan', 'tahun'));
    }
}