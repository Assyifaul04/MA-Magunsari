<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Pengaturan;

class GenerateAbsentRecords extends Command
{
    protected $signature = 'absensi:generate-tidak-hadir';
    protected $description = 'Menambahkan data tidak hadir untuk siswa yang tidak absen hari sebelumnya berdasarkan Pengaturan';

    public function handle()
    {
        // Ambil pengaturan tanggal kemarin
        $pengaturanKemarin = Pengaturan::orderBy('tanggal', 'desc')->skip(1)->first();

        if (!$pengaturanKemarin) {
            $this->info("Tidak ada pengaturan untuk tanggal kemarin. Proses dihentikan.");
            return;
        }

        $tanggalKemarin = $pengaturanKemarin->tanggal;

        $siswas = Siswa::whereNotNull('rfid')->get();

        foreach ($siswas as $siswa) {
            $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
                ->whereDate('tanggal', $tanggalKemarin)
                ->exists();

            if (!$sudahAbsen) {
                Absensi::create([
                    'siswa_id'   => $siswa->id,
                    'jenis'      => null,
                    'status'     => 'tidak hadir',
                    'rfid'       => $siswa->rfid,
                    'tanggal'    => $tanggalKemarin,
                    'jam'        => '00:00:00',
                    'keterangan' => 'Tidak melakukan absen',
                ]);
            }
        }

        $this->info("Data tidak hadir berhasil dibuat untuk tanggal {$tanggalKemarin}");
    }
}
