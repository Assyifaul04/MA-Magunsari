<?php

namespace App\Exports;

use App\Models\Absensi;
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

        $status = $this->request->status;
        if ($status === 'tidak_hadir') {
            $status = 'tidak hadir';
        }

        $absensiQuery = Absensi::with('siswa.kelas')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        if ($this->request->kelas) {
            $absensiQuery->whereHas('siswa', fn($q) => $q->where('kelas_id', $this->request->kelas));
        }
        if ($this->request->jenis) {
            $absensiQuery->where('jenis', $this->request->jenis);
        }
        if ($this->request->nama) {
            $absensiQuery->whereHas('siswa', fn($q) => $q->where('nama', 'like', "%{$this->request->nama}%"));
        }
        if ($status) {
            $absensiQuery->where('status', $status);
        }

        $absensi = $absensiQuery->orderBy('tanggal', 'desc')
                                ->orderBy('jam', 'asc')
                                ->get();

        return view('exports.absensi', compact('absensi'));
    }
}
