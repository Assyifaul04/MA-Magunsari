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
        $query = Absensi::with('siswa.kelas');

        if ($this->request->kelas) {
            $query->whereHas('siswa.kelas', function ($q) {
                $q->where('id', $this->request->kelas);
            });
        }
        if ($this->request->tanggal_mulai && $this->request->tanggal_selesai) {
            $query->whereBetween('tanggal', [$this->request->tanggal_mulai, $this->request->tanggal_selesai]);
        }
        if ($this->request->jenis) {
            $query->where('jenis', $this->request->jenis);
        }
        if ($this->request->nama) {
            $query->whereHas('siswa', function ($q) {
                $q->where('nama', 'like', "%{$this->request->nama}%");
            });
        }
        if ($this->request->status) {
            $query->where('status', $this->request->status);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->orderBy('jam', 'asc')->get();

        return view('exports.absensi', compact('absensi'));
    }
}
