<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Ambil kelas berdasarkan nama dari Excel, buat baru jika belum ada
        $kelas = Kelas::firstOrCreate(['nama' => $row['kelas']]);

        // Cek apakah kolom rfid ada
        $rfid = array_key_exists('rfid', $row) ? $row['rfid'] : null;

        // Cek apakah kolom status ada, default 'aktif' jika rfid ada, 'pending' jika rfid null
        $status = array_key_exists('status', $row) 
                    ? $row['status'] 
                    : ($rfid ? 'aktif' : 'pending');

        return new Siswa([
            'nama' => $row['nama'],
            'kelas_id' => $kelas->id,
            'rfid' => $rfid,
            'status' => $status,
        ]);
    }
}
