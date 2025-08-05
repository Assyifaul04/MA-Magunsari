<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::all();
        return view('admin.siswa.index', compact('siswas'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'kelas' => 'required|string',
            'rfid_uid' => 'required|string|unique:siswas,rfid_uid',
        ]);

        Siswa::create([
            'id' => Str::uuid(),
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'rfid_uid' => $request->rfid_uid,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        // Cek apakah hanya update RFID
        if ($request->has('rfid_uid')) {
            $request->validate([
                'rfid_uid' => 'required|string|unique:siswas,rfid_uid,' . $siswa->id,
            ]);
    
            $siswa->update([
                'rfid_uid' => $request->rfid_uid,
            ]);
    
            return response()->json(['status' => 'success']);
        }
    
        // Update data lengkap siswa (misalnya dari form edit)
        $request->validate([
            'nama' => 'required|string',
            'kelas' => 'required|string',
            'rfid_uid' => 'nullable|string|unique:siswas,rfid_uid,' . $siswa->id,
        ]);
    
        $siswa->update([
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'rfid_uid' => $request->rfid_uid ?? $siswa->rfid_uid,
        ]);
    
        return redirect()->route('siswa.index')->with('success', 'Data siswa diperbarui.');
    }
    

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');
        $data = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath())->getActiveSheet()->toArray();

        foreach ($data as $key => $row) {
            if ($key === 0) continue; // skip header

            Siswa::create([
                'id' => Str::uuid(),
                'nama' => $row[1] ?? 'Tanpa Nama',
                'kelas' => $row[2] ?? '-',
                'rfid_uid' => null, // Kosongkan dulu, biar diinput nanti
            ]);
        }

        return response()->json(['status' => 'success']);
    }
}
