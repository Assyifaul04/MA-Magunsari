<?php

namespace App\Jobs;

use App\Models\TemplateWhatsapp;
use App\Models\NotifikasiWhatsapp;
use App\Services\WhatsappService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SendWhatsappNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $siswa;
    protected $absensi;
    protected $jenis;
    protected $status;
    protected $waktu;

    /**
     * Create a new job instance.
     */
    public function __construct($siswa, $absensi, $jenis, $status, $waktu)
    {
        $this->siswa = $siswa;
        $this->absensi = $absensi;
        $this->jenis = $jenis;
        $this->status = $status;
        $this->waktu = $waktu;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsappService $waService)
    {
        // Jeda 10 detik agar terkirim 1 per 1 dan tidak memicu deteksi spam
        sleep(10);

        $template = TemplateWhatsapp::where('jenis', $this->jenis)
            ->where('is_active', true)
            ->first();

        if ($template && $this->siswa->orangTua && $this->siswa->orangTua->nomor_whatsapp) {
            $pesan = str_replace(
                ['{nama_siswa}', '{kelas}', '{tanggal}', '{jam}', '{status}'],
                [
                    $this->siswa->nama,
                    $this->siswa->kelas->nama ?? '-',
                    Carbon::parse($this->waktu)->format('d-m-Y'),
                    Carbon::parse($this->waktu)->format('H:i:s'),
                    $this->status
                ],
                $template->isi_pesan
            );

            try {
                $response = $waService->send($this->siswa->orangTua->nomor_whatsapp, $pesan);

                NotifikasiWhatsapp::create([
                    'absensi_id' => $this->absensi->id,
                    'siswa_id' => $this->siswa->id,
                    'orang_tua_id' => $this->siswa->orang_tua_id,
                    'nomor_whatsapp' => $this->siswa->orangTua->nomor_whatsapp,
                    'pesan' => $pesan,
                    'status' => 'terkirim',
                    'response_gateway' => json_encode($response),
                    'dikirim_pada' => now(),
                ]);
            } catch (\Exception $e) {
                NotifikasiWhatsapp::create([
                    'absensi_id' => $this->absensi->id,
                    'siswa_id' => $this->siswa->id,
                    'orang_tua_id' => $this->siswa->orang_tua_id,
                    'nomor_whatsapp' => $this->siswa->orangTua->nomor_whatsapp,
                    'pesan' => $pesan,
                    'status' => 'gagal',
                    'response_gateway' => $e->getMessage(),
                ]);
            }
        }
    }
}