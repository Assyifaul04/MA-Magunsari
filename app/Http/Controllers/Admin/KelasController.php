<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Guru; // Jangan lupa import model Guru

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('waliKelas')->get();
        $gurus = Guru::all(); 
        
        return view('admin.kelas.index', compact('kelas', 'gurus'));
    }

    public function create()
    {
        $gurus = Guru::all();
        return view('admin.kelas.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'guru_id' => 'nullable|exists:gurus,id'
        ]);

        if ($request->filled('guru_id')) {
            $jumlahKelas = Kelas::where('guru_id', $request->guru_id)->count();
            if ($jumlahKelas >= 2) {
                return redirect()->back()->with('error', 'Gagal! Guru ini sudah menjadi wali di 2 kelas.')->withInput();
            }
        }

        Kelas::create($request->all());
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        $gurus = Guru::all();
        return view('admin.kelas.edit', compact('kelas', 'gurus'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'guru_id' => 'nullable|exists:gurus,id'
        ]);

        if ($request->filled('guru_id') && $request->guru_id != $kelas->guru_id) {
            $jumlahKelas = Kelas::where('guru_id', $request->guru_id)->count();
            if ($jumlahKelas >= 2) {
                return redirect()->back()->with('error', 'Gagal! Guru ini sudah menjadi wali di 2 kelas.')->withInput();
            }
        }

        $kelas->update($request->all());
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kelas berhasil dihapus.']);
        }

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}