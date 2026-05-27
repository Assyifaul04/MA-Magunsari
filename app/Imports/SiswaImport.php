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
        // Cegah error jika baris atau kolom wajib ada yang kosong
        if (
            empty($row['nisn']) ||
            empty($row['nama']) ||
            empty($row['kelas'])
        ) {
            return null;
        }

        // Cari atau buat kelas berdasarkan nama kelas dari Excel
        $kelas = Kelas::firstOrCreate([
            'nama' => $row['kelas']
        ]);

        // Simpan data siswa ke database
        return new Siswa([
            'nisn'         => $row['nisn'],
            'nama'         => $row['nama'],
            'kelas_id'     => $kelas->id,
            'orang_tua_id' => null,       // Otomatis diisi NULL di backend
            'rfid'         => null,       // Otomatis diisi NULL di backend
            'status'       => 'pending',  // Otomatis diisi 'pending' di backend
        ]);
    }
}