<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class AbsensiExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $tanggalMulai   = $this->request->tanggal_mulai ?? now()->toDateString();
        $tanggalSelesai = $this->request->tanggal_selesai ?? now()->toDateString();

        // Query absensi (kecuali tidak_hadir)
        $absensiQuery = Absensi::with('siswa.kelas')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        if ($this->request->kelas) {
            $absensiQuery->whereHas('siswa.kelas', fn($q) => $q->where('id', $this->request->kelas));
        }
        if ($this->request->jenis) {
            $absensiQuery->where('jenis', $this->request->jenis);
        }
        if ($this->request->nama) {
            $absensiQuery->whereHas('siswa', fn($q) => $q->where('nama', 'like', "%{$this->request->nama}%"));
        }
        if ($this->request->status && $this->request->status !== 'tidak_hadir') {
            $absensiQuery->where('status', $this->request->status);
        }

        $absensi = $absensiQuery
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'asc')
            ->get();

        // Query siswa tidak hadir (hanya jika status tidak_hadir atau status kosong)
        $siswaTidakHadir = collect();
        if ($this->request->status === 'tidak_hadir' || !$this->request->status) {
            $siswaTidakHadir = Siswa::with('kelas')
                ->whereNotNull('rfid')
                ->when($this->request->kelas, fn($q) => $q->where('kelas_id', $this->request->kelas))
                ->when($this->request->nama, fn($q) => $q->where('nama', 'like', "%{$this->request->nama}%"))
                ->whereDoesntHave('absensi', function ($q) use ($tanggalMulai, $tanggalSelesai) {
                    $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                        ->whereIn('status', ['hadir', 'terlambat', 'izin', 'sakit', 'pulang']);
                })
                ->get();
        }

        return view('exports.absensi', compact('absensi', 'siswaTidakHadir'));
    }
}
