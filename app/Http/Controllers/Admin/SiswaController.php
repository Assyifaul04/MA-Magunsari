<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\OrangTua; // 1. Tambahkan model OrangTua di sini
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with(['kelas', 'orangTua'])->get();
        $kelas = Kelas::all();
        $orangTuas = OrangTua::all(); 
        return view('admin.siswa.index', compact('siswas', 'kelas', 'orangTuas'));
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
            'orang_tua_id' => 'nullable|exists:orang_tuas,id',
        ]);

        $siswa = Siswa::create([
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
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
            'orang_tua_id' => 'nullable|exists:orang_tuas,id',
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
        $siswa->load(['kelas', 'orangTua']);
        return view('admin.siswa.show', compact('siswa'));
    }

    // Fungsi destroy, scan, dan import tetap sama seperti kode Anda sebelumnya
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
