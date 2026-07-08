<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use App\Models\TemplateWhatsapp;
use App\Models\NotifikasiWhatsapp;
use App\Services\WhatsappService;
use Carbon\Carbon;

class SendRekapAbsensiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:send-rekap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pesan gabungan masuk dan pulang ke orang tua setiap kali siswa scan pulang.';

    /**
     * Execute the console command.
     */
    public function handle(WhatsappService $waService)
    {
        $today = Carbon::today();

        // Cari siswa yang absen PULANG hari ini
        $absensiPulangList = Absensi::with(['siswa.kelas', 'siswa.orangTua'])
            ->whereDate('tanggal', $today)
            ->where('jenis', 'pulang')
            ->get();

        if ($absensiPulangList->isEmpty()) {
            return Command::SUCCESS;
        }

        $template = TemplateWhatsapp::where('jenis', 'rekap_harian')
            ->where('is_active', true)
            ->first();

        if (!$template) {
            return Command::FAILURE; // Tidak ada template
        }

        foreach ($absensiPulangList as $absenPulang) {
            $siswa = $absenPulang->siswa;

            // Skip jika data tidak lengkap
            if (!$siswa || !$siswa->orangTua || !$siswa->orangTua->nomor_whatsapp) {
                continue;
            }

            // CEK: Apakah kita SUDAH MENGIRIM notifikasi ke siswa ini HARI INI?
            $sudahDikirim = NotifikasiWhatsapp::where('siswa_id', $siswa->id)
                ->whereDate('dikirim_pada', $today)
                ->exists();

            if ($sudahDikirim) {
                continue; // Jangan kirim 2x
            }

            // Ambil data Absen Masuk untuk siswa ini hari ini
            $absenMasuk = Absensi::where('siswa_id', $siswa->id)
                ->whereDate('tanggal', $today)
                ->where('jenis', 'masuk')
                ->first();

            $jamMasuk = $absenMasuk ? Carbon::parse($absenMasuk->jam)->format('H:i') . ' WIB' : '-';
            $jamPulang = Carbon::parse($absenPulang->jam)->format('H:i') . ' WIB';

            // Format Pesan
            $pesan = str_replace(
                ['{nama_siswa}', '{kelas}', '{tanggal}', '{jam_masuk}', '{jam_pulang}'],
                [
                    $siswa->nama,
                    $siswa->kelas->nama ?? '-',
                    Carbon::parse($absenPulang->tanggal)->isoFormat('D MMMM YYYY'),
                    $jamMasuk,
                    $jamPulang
                ],
                $template->isi_pesan
            );

            // Kirim WhatsApp
            try {
                $response = $waService->send($siswa->orangTua->nomor_whatsapp, $pesan);

                NotifikasiWhatsapp::create([
                    'absensi_id' => $absenPulang->id,
                    'siswa_id' => $siswa->id,
                    'orang_tua_id' => $siswa->orang_tua_id,
                    'nomor_whatsapp' => $siswa->orangTua->nomor_whatsapp,
                    'pesan' => $pesan,
                    'status' => 'terkirim',
                    'response_gateway' => json_encode($response),
                    'dikirim_pada' => now(),
                ]);
            } catch (\Exception $e) {
                NotifikasiWhatsapp::create([
                    'absensi_id' => $absenPulang->id,
                    'siswa_id' => $siswa->id,
                    'orang_tua_id' => $siswa->orang_tua_id,
                    'nomor_whatsapp' => $siswa->orangTua->nomor_whatsapp,
                    'pesan' => $pesan,
                    'status' => 'gagal',
                    'response_gateway' => $e->getMessage(),
                    'dikirim_pada' => now(),
                ]);
            }

            // DELAY 1 MENIT per PESAN sesuai instruksi
            sleep(60); 
        }

        return Command::SUCCESS;
    }
}