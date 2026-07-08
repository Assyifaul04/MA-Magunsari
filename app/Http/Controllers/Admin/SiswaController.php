<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\OrangTua;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use App\Imports\SiswaImport;

class SiswaController extends Controller
{
    // Hanya menampilkan siswa AKTIF & PENDING (non_aktif tidak ikut tercampur)
    public function index()
    {
        $siswas = Siswa::with(['kelas', 'orangTua'])
            ->aktif() // scope: whereIn status [aktif, pending]
            ->orderByDesc('angkatan')
            ->get();

        $kelas = Kelas::all();
        $orangTuas = OrangTua::all();
        $daftarAngkatan = Siswa::select('angkatan')->distinct()->orderByDesc('angkatan')->pluck('angkatan');

        return view('admin.siswa.index', compact('siswas', 'kelas', 'orangTuas', 'daftarAngkatan'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $orangTuas = OrangTua::all();
        return view('admin.siswa.create', compact('kelas', 'orangTuas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|max:20|unique:siswas,nisn',
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'angkatan' => 'required|integer|digits:4',
            'orang_tua_id' => 'nullable|exists:orang_tuas,id',
        ]);

        $siswa = Siswa::create([
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
            'angkatan' => $request->angkatan,
            'orang_tua_id' => $request->orang_tua_id,
            'rfid' => null,
            'status' => 'pending',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan',
            ]);
        }

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan');
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        $orangTuas = OrangTua::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas', 'orangTuas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn' => 'required|string|max:20|unique:siswas,nisn,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'angkatan' => 'required|integer|digits:4',
            'orang_tua_id' => 'nullable|exists:orang_tuas,id',
            'rfid' => 'nullable|unique:siswas,rfid,' . $siswa->id,
            'status' => 'required|in:aktif,pending,non_aktif',
        ]);

        $data = $request->all();

        // FAIL-SAFE LOGIC:
        // Hanya berlaku kalau admin TIDAK sedang menetapkan status jadi non_aktif.
        if ($data['status'] !== 'non_aktif') {
            $data['status'] = empty($data['rfid']) ? 'pending' : 'aktif';
        }

        $siswa->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diperbarui',
            ]);
        }

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas', 'orangTua']);
        return view('admin.siswa.show', compact('siswa'));
    }

    public function destroy(Request $request, Siswa $siswa)
    {
        $siswa->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dihapus',
            ]);
        }

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'rfid' => 'required',
        ]);

        $exists = Siswa::where('rfid', $request->rfid)
            ->where('id', '<>', $request->siswa_id)
            ->exists();

        if ($exists) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'RFID sudah digunakan oleh siswa lain.'
                ], 422);
            }
            return redirect()->back()->with('error', 'RFID sudah digunakan oleh siswa lain.');
        }

        $siswa = Siswa::findOrFail($request->siswa_id);

        if ($siswa->status === 'non_aktif') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa ini berstatus non_aktif, RFID tidak dapat diperbarui lewat scan.'
                ], 422);
            }
            return redirect()->back()->with('error', 'Siswa ini berstatus non_aktif, RFID tidak dapat diperbarui lewat scan.');
        }

        $siswa->rfid = $request->rfid;
        $siswa->status = 'aktif';
        $siswa->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'RFID berhasil disimpan dan status diubah menjadi aktif untuk ' . $siswa->nama,
                'siswa_id' => $siswa->id,
                'rfid' => $siswa->rfid,
                'status' => $siswa->status,
            ]);
        }

        return redirect()->route('siswa.index')->with('success', 'RFID berhasil disimpan dan status diubah menjadi aktif untuk ' . $siswa->nama);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $path = public_path('uploads/' . $filename);
            Excel::import(new SiswaImport, $path);
            if (file_exists($path)) {
                unlink($path);
            }

            return redirect()
                ->route('siswa.index')
                ->with('success', 'Data siswa berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()
                ->route('siswa.index')
                ->with('error', $e->getMessage());
        }
    }

    // ==== FITUR ANGKATAN & non_aktif (halaman terpisah dari index) ====

    public function non_aktif(Request $request)
    {
        $query = Siswa::with(['kelas', 'orangTua'])->non_aktif();

        if ($request->filled('angkatan')) {
            $query->angkatan($request->angkatan);
        }

        $siswas = $query->orderByDesc('angkatan')->get();

        $daftarAngkatan = Siswa::non_aktif()
            ->select('angkatan')
            ->distinct()
            ->orderByDesc('angkatan')
            ->pluck('angkatan');

        return view('admin.siswa.non_aktif', compact('siswas', 'daftarAngkatan'));
    }

    public function formLuluskanAngkatan()
    {
        // Semua siswa aktif/pending
        $siswas = Siswa::with('kelas')
            ->aktif()
            ->orderBy('angkatan', 'desc')
            ->orderBy('nama')
            ->get();

        $daftarAngkatan = Siswa::aktif()
            ->select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');


        $daftarKelas = Kelas::orderBy('nama')->get();

        return view('admin.siswa.luluskan', compact('daftarAngkatan', 'siswas', 'daftarKelas'));
    }

    public function luluskanAngkatan(Request $request)
    {

        $request->validate([
            'angkatan' => 'required|integer|digits:4',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $jumlah = Siswa::where('angkatan', $request->angkatan)
            ->where('kelas_id', $request->kelas_id)
            ->where('status', '!=', 'non_aktif')
            ->update(['status' => 'non_aktif']);

        $kelas = Kelas::find($request->kelas_id);
        $namaKelas = $kelas ? $kelas->nama : 'Kelas Tidak Diketahui';

        if ($jumlah === 0) {
            $message = "Tidak ada siswa angkatan {$request->angkatan} di {$namaKelas} yang bisa diluluskan.";
            $success = false;
        } else {
            $message = "Berhasil nonaktifkan {$jumlah} siswa angkatan {$request->angkatan} (Khusus {$namaKelas}).";
            $success = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
        }

        return redirect()->route('siswa.index')->with($success ? 'success' : 'error', $message);
    }

    public function batalkanAlumni(Request $request, Siswa $siswa)
    {
        $siswa->update(['status' => 'aktif']);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $siswa->nama . ' dikembalikan menjadi siswa aktif.',
            ]);
        }

        return redirect()->route('siswa.non_aktif')->with('success', $siswa->nama . ' dikembalikan menjadi siswa aktif.');
    }
}