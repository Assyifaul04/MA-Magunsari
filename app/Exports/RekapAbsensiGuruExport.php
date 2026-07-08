<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RekapAbsensiGuruExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $rekap;
    protected $jumlahHari;
    protected $namaKelas;
    protected $namaBulan;
    protected $tahun;

    public function __construct($rekap, $jumlahHari, $namaKelas, $namaBulan, $tahun)
    {
        $this->rekap = $rekap;
        $this->jumlahHari = $jumlahHari;
        $this->namaKelas = $namaKelas;
        $this->namaBulan = $namaBulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        return view('guru.absensi.export_excel', [
            'rekap' => $this->rekap,
            'jumlahHari' => $this->jumlahHari,
            'namaKelas' => $this->namaKelas,
            'namaBulan' => $this->namaBulan,
            'tahun' => $this->tahun
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Styling untuk menyesuaikan mirip dengan gambar
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->jumlahHari + 6);
        $totalRows = count($this->rekap) + 5; // Header + Data rows

        $sheet->getStyle('A1:'.$lastCol.$totalRows)->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'size' => 11,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        // Borders seluruh tabel
        $sheet->getStyle('A4:'.$lastCol.$totalRows)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        // Text align left untuk nama
        $sheet->getStyle('B6:B'.$totalRows)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        return [];
    }
}