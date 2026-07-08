<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\PengaturanWa;

class WhatsappService
{
    public function send($target, $message)
    {
        // 1. Ambil pengaturan dari database
        $pengaturan = PengaturanWa::first();

        // 2. Cek apakah token ada di database
        if (!$pengaturan || empty($pengaturan->fonnte_token)) {
            throw new \Exception("Gagal: Token Fonnte belum diatur di Pengaturan WA.");
        }

        $token = $pengaturan->fonnte_token;

        // 3. Kirim pesan menggunakan token dari database
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);

        return $response->json();
    }
}