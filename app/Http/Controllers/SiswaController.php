<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with('kelas')->get();
        $kelas = Kelas::all();
        return view('master.siswa.index', compact('siswas', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('master.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa = Siswa::create([
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
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
        return view('master.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'rfid' => 'nullable|unique:siswas,rfid,' . $siswa->id,
            'status' => 'required|in:aktif,pending',
        ]);

        $siswa->update($request->all());

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
        return view('master.siswa.show', compact('siswa'));
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

        // Check if RFID already exists for another student
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
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));
            return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->route('siswa.index')->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
}