<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappService
{
    public function send($target, $message)
    {
        // Hapus tulisan Fonnte jika ikut masuk
        $message = str_replace([
            'Fonnte',
            'fonnte',
            "\nFonnte",
            "\n\nFonnte"
        ], '', $message);

        $response = Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN')
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => trim($message),
        ]);

        return $response->json();
    }
}