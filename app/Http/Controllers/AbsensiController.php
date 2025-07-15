<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{

    public function index()
    {
        $data = Siswa::all();
        return view('import', ['data' => $data]);
    }

    public function updateRfid(Request $request, $id)
    {

        $request->validate([
            'rfid' => 'required|unique:siswas,rfid_uid'
        ], [
            'rfid.unique' => 'RFID Sudah digunakan.'
        ]);

        try {
            Siswa::where('id', $id)->update([
                'rfid_uid' => $request->rfid
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'RFID UUID BERHASIL DIPASANGKAN'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal error' . $e->getMessage()
            ], 500);
        }
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,xlsm'
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diimport!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal import: ' . $e->getMessage()
            ], 500);
        }
    }
}
