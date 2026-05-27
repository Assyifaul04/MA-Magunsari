<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\OrangTuaImport;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrangTuaController extends Controller
{
    public function index()
    {
        $orangTuas = OrangTua::latest()->get();

        return view('admin.orangtua.index', compact('orangTuas'));
    }

    public function create()
    {
        return view('admin.orangtua.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'nomor_whatsapp' => 'required|string|unique:orang_tuas,nomor_whatsapp',
            'alamat' => 'nullable|string',
        ]);

        OrangTua::create($validated);

        return redirect()
            ->route('orangtua.index')
            ->with('success', 'Data orang tua berhasil ditambahkan');
    }

    public function edit(OrangTua $orangTua)
    {
        return view('admin.orangtua.edit', compact('orangTua'));
    }

    public function update(Request $request, OrangTua $orangTua)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'nomor_whatsapp' => 'required|string|unique:orang_tuas,nomor_whatsapp,' . $orangTua->id,
            'alamat' => 'nullable|string',
        ]);

        $orangTua->update($validated);

        return redirect()
            ->route('orangtua.index')
            ->with('success', 'Data orang tua berhasil diupdate');
    }

    public function destroy(OrangTua $orangTua)
    {
        $orangTua->delete();

        return back()->with('success', 'Data orang tua berhasil dihapus');
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv'
    ]);

    try {

        // SOLUSI AGAR TIDAK PATH CANNOT BE EMPTY
        $file = $request->file('file');
        $fileName = time() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('imports'), $fileName);

        Excel::import(
            new OrangTuaImport,
            public_path('imports/' . $fileName)
        );

        return redirect()->route('orangtua.index')
            ->with('success', 'Data orang tua berhasil diimport.');

    } catch (\Exception $e) {

        return redirect()->route('orangtua.index')
            ->with('error', 'Gagal import: ' . $e->getMessage());
    }
}
}