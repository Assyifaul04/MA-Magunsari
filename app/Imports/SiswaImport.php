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
        // Kolom 'no' tidak perlu di-cek karena biasanya hanya untuk nomor urut visual di Excel
        if (
            empty($row['nisn']) ||
            empty($row['nama']) ||
            empty($row['kelas']) ||
            empty($row['angkatan']) // <-- Tambahan pengecekan kolom angkatan
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
            'angkatan'     => $row['angkatan'], // <-- Menyimpan data angkatan dari Excel
            'orang_tua_id' => null,             // Otomatis diisi NULL di backend
            'rfid'         => null,             // Otomatis diisi NULL di backend
            'status'       => 'pending',        // Otomatis diisi 'pending' di backend
        ]);
    }
}