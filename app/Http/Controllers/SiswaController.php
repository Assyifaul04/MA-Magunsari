<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SiswaExport;
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
            'rfid' => 'required|unique:siswas,rfid',
            'status' => 'required|in:aktif,pending',
        ]);

        Siswa::create($request->all());
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        return view('master.siswa.edit', compact('siswa', 'kelas'));
    }
    

    public function update(Request $request, Siswa $siswa)
    {
        if ($request->ajax()) {
            $request->validate([
                'rfid' => 'required|unique:siswas,rfid,' . $siswa->id,
            ]);
    
            $siswa->rfid = $request->rfid;
            $siswa->save();
    
            return response()->json([
                'success' => true,
                'message' => 'RFID berhasil disimpan untuk ' . $siswa->nama,
                'siswa_id' => $siswa->id,
                'rfid' => $siswa->rfid,
            ]);
        }
    
        // request biasa (form)
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'rfid' => 'required|unique:siswas,rfid,' . $siswa->id,
            'status' => 'required|in:aktif,pending',
        ]);
    
        $siswa->update($request->all());
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function show(Siswa $siswa)
    {
        return view('master.siswa.show', compact('siswa'));
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus.');
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
        if($exists) {
            return response()->json([
                'success' => false,
                'message' => 'RFID sudah digunakan oleh siswa lain.'
            ], 422);
        }
    
        $siswa = Siswa::findOrFail($request->siswa_id);
        $siswa->rfid = $request->rfid;
        $siswa->status = 'aktif';
        $siswa->save();
    
        return response()->json([
            'success' => true,
            'message' => 'RFID berhasil disimpan dan status diubah menjadi aktif untuk ' . $siswa->nama,
            'siswa_id' => $siswa->id,
            'rfid' => $siswa->rfid,
            'status' => $siswa->status,
        ]);
    }
    

    // Import Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new SiswaImport, $request->file('file'));
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diimport.');
    }
}
