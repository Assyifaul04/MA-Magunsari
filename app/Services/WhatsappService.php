<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\PengaturanWa;

class WhatsappService
{
    public function send($target, $message)
    {

        $pengaturan = PengaturanWa::first();

        if (!$pengaturan || empty($pengaturan->fonnte_token)) {
            throw new \Exception("Gagal: Token Fonnte belum diatur di Pengaturan WA.");
        }

        $token = $pengaturan->fonnte_token;

        $response = Http::withHeaders([
            'Authorization' => $token
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);

        return $response->json();
    }
}