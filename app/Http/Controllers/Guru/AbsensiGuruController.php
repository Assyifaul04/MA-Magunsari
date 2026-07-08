<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Pengaturan;
use App\Exports\RekapAbsensiGuruExport;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiGuruController extends Controller
{
    private function generateRangeGuru($tanggalMulai, $tanggalSelesai, $kelasIds)
    {
        $siswaList = Siswa::whereNotNull('rfid')->whereIn('kelas_id', $kelasIds)->get();
        $periode = CarbonPeriod::create($tanggalMulai, $tanggalSelesai);

        $now = Carbon::now();
        $hariIni = $now->toDateString();
        $pengaturan = Pengaturan::where('tanggal', $hariIni)->first();
        $jamPulang = $pengaturan->jam_pulang ?? '15:00:00';

        foreach ($periode as $tanggal) {
            $tanggalCek = $tanggal->toDateString();
            if ($tanggalCek === $hariIni && $now->format('H:i:s') <= $jamPulang) continue;
            if ($tanggal->isFuture()) continue;

            foreach ($siswaList as $siswa) {
                $cek = Absensi::where('siswa_id', $siswa->id)->whereDate('tanggal', $tanggalCek)->exists();
                if (!$cek) {
                    Absensi::create([
                        'siswa_id'   => $siswa->id,
                        'status'     => 'tidak hadir',
                        'rfid'       => $siswa->rfid,
                        'keterangan' => 'tidak melakukan absen',
                        'tanggal'    => $tanggalCek,
                        'jam'        => '00:00:00'
                    ]);
                }
            }
        }
    }

    public function hariIni(Request $request)
    {
        $guru = Guru::with('kelas')->where('user_id', Auth::id())->first();
        if (!$guru || $guru->kelas->isEmpty()) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda belum ditugaskan menjadi Wali Kelas.');
        }

        $kelasIds = $guru->kelas->pluck('id');
        $tanggalMulai   = $request->input('tanggal_mulai', Carbon::today()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::today()->toDateString());
        $statusFilter   = $request->input('status');

        $this->generateRangeGuru($tanggalMulai, $tanggalSelesai, $kelasIds);

        $query = Absensi::with(['siswa.kelas'])
            ->whereHas('siswa', function($q) use ($kelasIds) {
                $q->whereIn('kelas_id', $kelasIds);
            })
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->whereNotIn('status', ['sakit', 'izin']);

        if ($statusFilter && in_array($statusFilter, ['hadir', 'terlambat', 'tidak hadir'])) {
            $query->where('status', $statusFilter);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->orderBy('jam', 'asc')->get();

        return view('guru.absensi.hari_ini', compact('absensi', 'tanggalMulai', 'tanggalSelesai', 'statusFilter'));
    }

    public function updateManual(Request $request, $siswaId)
    {
        $request->validate([
            'status'     => 'required|in:hadir,sakit,izin,alfa,terlambat',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $siswa = Siswa::findOrFail($siswaId);
        $hariIni = Carbon::today()->toDateString();

        Absensi::updateOrCreate(
            ['siswa_id' => $siswa->id, 'tanggal'  => $hariIni],
            [
                'status'     => $request->status,
                'jam'        => in_array($request->status, ['hadir', 'terlambat']) ? Carbon::now()->toTimeString() : '00:00:00',
                'keterangan' => $request->keterangan ?? 'Diinput manual oleh Wali Kelas'
            ]
        );

        return redirect()->back()->with('success', 'Status kehadiran ' . $siswa->nama . ' berhasil diperbarui.');
    }

    /**
     * Menyiapkan matriks data rekap bulanan per kelas
     */
    private function getMatriksRekap($bulan, $tahun, $kelas_id)
    {
        $jumlahHari = Carbon::createFromDate($tahun, $bulan)->daysInMonth;
        $siswas = Siswa::where('kelas_id', $kelas_id)->orderBy('nama', 'asc')->get();
        
        $absensi = Absensi::whereIn('siswa_id', $siswas->pluck('id'))
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->groupBy('siswa_id');

        $rekap = [];
        foreach ($siswas as $siswa) {
            $rekap[$siswa->id] = [
                'siswa' => $siswa,
                'data'  => [],
                'total' => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alfa' => 0]
            ];

            for ($hari = 1; $hari <= $jumlahHari; $hari++) {
                $tanggal = Carbon::createFromDate($tahun, $bulan, $hari)->toDateString();
                $absenHari = optional($absensi->get($siswa->id) ?? collect())->firstWhere('tanggal', $tanggal);

                if ($absenHari) {
                    $st = strtolower($absenHari->status);
                    if (in_array($st, ['hadir', 'terlambat'])) {
                        $rekap[$siswa->id]['data'][$hari] = 'H';
                        $rekap[$siswa->id]['total']['hadir']++;
                    } elseif ($st == 'izin') {
                        $rekap[$siswa->id]['data'][$hari] = 'I';
                        $rekap[$siswa->id]['total']['izin']++;
                    } elseif ($st == 'sakit') {
                        $rekap[$siswa->id]['data'][$hari] = 'S';
                        $rekap[$siswa->id]['total']['sakit']++;
                    } else {
                        $rekap[$siswa->id]['data'][$hari] = 'A'; // Alfa / Tidak Hadir
                        $rekap[$siswa->id]['total']['alfa']++;
                    }
                } else {
                    $rekap[$siswa->id]['data'][$hari] = '';
                }
            }
        }

        return ['rekap' => $rekap, 'jumlahHari' => $jumlahHari];
    }

    public function rekap(Request $request)
    {
        $guru = Guru::with('kelas')->where('user_id', Auth::id())->first();
        if (!$guru || $guru->kelas->isEmpty()) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda belum ditugaskan menjadi Wali Kelas.');
        }

        $kelasList = $guru->kelas;
        $kelasIds = $kelasList->pluck('id');

        // PERBAIKAN: Cast string dari request input menjadi Integer
        $bulan = (int) $request->input('bulan', Carbon::now()->month);
        $tahun = (int) $request->input('tahun', Carbon::now()->year);
        $kelas_id = $request->input('kelas_id', $kelasIds->first());

        if (!$kelasIds->contains($kelas_id)) {
            $kelas_id = $kelasIds->first();
        }

        $dataRekap = $this->getMatriksRekap($bulan, $tahun, $kelas_id);
        
        $rekap = $dataRekap['rekap'];
        $jumlahHari = $dataRekap['jumlahHari'];

        return view('guru.absensi.rekap', compact('rekap', 'tahun', 'bulan', 'jumlahHari', 'kelasList', 'kelas_id'));
    }

    public function exportExcel(Request $request)
    {
        $guru = Guru::with('kelas')->where('user_id', Auth::id())->first();
        $kelasIds = $guru->kelas->pluck('id');

        // PERBAIKAN: Cast string dari request input menjadi Integer
        $bulan = (int) $request->input('bulan', Carbon::now()->month);
        $tahun = (int) $request->input('tahun', Carbon::now()->year);
        $kelas_id = $request->input('kelas_id', $kelasIds->first());

        if (!$kelasIds->contains($kelas_id)) abort(403);

        $dataRekap = $this->getMatriksRekap($bulan, $tahun, $kelas_id);
        $namaKelas = \App\Models\Kelas::find($kelas_id)->nama ?? 'Kelas';
        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');

        $fileName = 'Rekap_Absensi_' . str_replace(' ', '_', $namaKelas) . '_' . $namaBulan . '_' . $tahun . '.xlsx';

        return Excel::download(new RekapAbsensiGuruExport($dataRekap['rekap'], $dataRekap['jumlahHari'], $namaKelas, $namaBulan, $tahun), $fileName);
    }
}