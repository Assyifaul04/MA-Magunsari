<?php

namespace App\Imports;

use App\Models\OrangTua;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrangTuaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip baris kosong
        if (
            empty($row['nama']) ||
            empty($row['nomor_whatsapp'])
        ) {
            return null;
        }

        return new OrangTua([
            'nama' => $row['nama'],
            'nomor_whatsapp' => $row['nomor_whatsapp'],
            'alamat' => $row['alamat'] ?? null,
        ]);
    }
}