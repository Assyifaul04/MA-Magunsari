<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengaturanWa;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PengaturanWaController extends Controller
{
    // Mengambil data pengaturan utama
    private function getPengaturan()
    {
        return PengaturanWa::firstOrCreate(
            ['id' => 1],
            ['status_koneksi' => 'disconnect']
        );
    }

    // Halaman Utama Pengaturan
    public function index()
    {
        $pengaturan = $this->getPengaturan();
        $qrCode = null;
        $errorApi = null;

        if ($pengaturan->fonnte_token) {
            try {
                // 1. Panggil API pertama: Cek Status Perangkat
                $response = Http::withHeaders([
                    'Authorization' => $pengaturan->fonnte_token
                ])->timeout(10)->post('https://api.fonnte.com/device');

                if ($response->successful()) {
                    $result = $response->json();
                    
                    // Normalisasi status
                    $rawStatus = $result['device_status'] ?? 'disconnect';
                    $isConnect = in_array(strtolower($rawStatus), ['connect', 'connected', 'authenticated']);
                    $statusKoneksi = $isConnect ? 'connect' : 'disconnect';

                    // Update data ke database lokal
                    $pengaturan->update([
                        'status_koneksi' => $statusKoneksi,
                        'nama_perangkat' => $result['name'] ?? '-',
                        'nomor_wa' => $result['device'] ?? '-',
                        'sisa_kuota' => $result['quota'] ?? 0,
                        'masa_aktif' => $result['expired'] ?? '-',
                    ]);

                    // 2. Panggil API kedua: Khusus Meminta QR Code (JIKA STATUS DISCONNECT)
                    if ($statusKoneksi === 'disconnect') {
                        // Cek cache untuk menghindari rate limit
                        $cacheKey = 'qr_code_' . md5($pengaturan->fonnte_token);
                        
                        if (Cache::has($cacheKey)) {
                            // Ambil dari cache jika masih ada
                            $qrCode = Cache::get($cacheKey);
                        } else {
                            // Panggil API QR
                            $qrResponse = Http::withHeaders([
                                'Authorization' => $pengaturan->fonnte_token
                            ])->timeout(15)->post('https://api.fonnte.com/qr');

                            if ($qrResponse->successful()) {
                                $qrResult = $qrResponse->json();
                                
                                // Menurut dokumentasi Fonnte, string gambar base64 ada di indeks 'url'
                                if (isset($qrResult['status']) && $qrResult['status'] === true && !empty($qrResult['url'])) {
                                    $qrCode = $qrResult['url'];
                                    // Simpan di cache selama 30 detik (hindari rate limit)
                                    Cache::put($cacheKey, $qrCode, 30);
                                } elseif (isset($qrResult['reason'])) {
                                    // Jika rate limit, beri pesan yang jelas
                                    if (strpos($qrResult['reason'], 'rate limit') !== false || strpos($qrResult['reason'], 'Rate limit') !== false) {
                                        $errorApi = 'Rate limit API Fonnte. Silakan tunggu beberapa saat dan refresh halaman.';
                                    } else {
                                        $errorApi = "Gagal memuat QR dari Fonnte: " . $qrResult['reason'];
                                    }
                                }
                            } else {
                                $errorApi = 'Gagal meminta QR Code dari server Fonnte.';
                            }
                        }
                    }
                    
                } else {
                    $errorApi = 'Token tidak valid atau terjadi masalah pada server Fonnte.';
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $errorApi = 'Koneksi ke server Fonnte gagal. Periksa koneksi internet Anda.';
            } catch (\Exception $e) {
                $errorApi = 'Gagal terhubung ke API Fonnte: ' . $e->getMessage();
            }
        }

        return view('admin.pengaturan_wa.index', compact('pengaturan', 'qrCode', 'errorApi'));
    }

    // ENDPOINT CEK STATUS (untuk polling JavaScript)
    public function cekStatus(Request $request)
    {
        // Hanya untuk request AJAX
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $pengaturan = $this->getPengaturan();

        if (empty($pengaturan->fonnte_token)) {
            return response()->json([
                'status' => 'disconnect',
                'message' => 'Token tidak ditemukan'
            ]);
        }

        try {
            // Cek status ke Fonnte API
            $response = Http::withHeaders([
                'Authorization' => $pengaturan->fonnte_token
            ])->timeout(10)->post('https://api.fonnte.com/device');

            if ($response->successful()) {
                $result = $response->json();
                
                // Normalisasi status
                $rawStatus = $result['device_status'] ?? 'disconnect';
                $isConnect = in_array(strtolower($rawStatus), ['connect', 'connected', 'authenticated']);
                $statusKoneksi = $isConnect ? 'connect' : 'disconnect';

                // Update database
                $pengaturan->update([
                    'status_koneksi' => $statusKoneksi,
                    'nama_perangkat' => $result['name'] ?? '-',
                    'nomor_wa' => $result['device'] ?? '-',
                    'sisa_kuota' => $result['quota'] ?? 0,
                    'masa_aktif' => $result['expired'] ?? '-',
                ]);

                return response()->json([
                    'status' => $statusKoneksi,
                    'device' => $result['device'] ?? '-',
                    'name' => $result['name'] ?? '-',
                    'quota' => $result['quota'] ?? 0
                ]);
            }

            return response()->json([
                'status' => 'disconnect',
                'error' => 'Gagal mengecek status'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Menyimpan atau memperbarui Token Fonnte
    public function updateToken(Request $request)
    {
        $request->validate([
            'fonnte_token' => 'required|string|min:10'
        ]);

        $pengaturan = $this->getPengaturan();
        
        // Hapus cache QR Code lama
        $cacheKey = 'qr_code_' . md5($pengaturan->fonnte_token);
        Cache::forget($cacheKey);
        
        $pengaturan->update([
            'fonnte_token' => trim($request->fonnte_token)
        ]);

        return redirect()->back()->with('success', 'Token Fonnte berhasil diperbarui.');
    }

    // Memutus koneksi perangkat (Disconnect/Logout Perangkat)
    public function disconnectDevice()
    {
        $pengaturan = $this->getPengaturan();

        if ($pengaturan->fonnte_token) {
            try {
                Http::withHeaders([
                    'Authorization' => $pengaturan->fonnte_token
                ])->timeout(10)->post('https://api.fonnte.com/disconnect');
                
                // Update status lokal
                $pengaturan->update([
                    'status_koneksi' => 'disconnect'
                ]);

                // Hapus cache QR Code
                $cacheKey = 'qr_code_' . md5($pengaturan->fonnte_token);
                Cache::forget($cacheKey);
                
            } catch (\Exception $e) {
                // Tetap lanjutkan walaupun gagal
            }
        }

        return redirect()->back()->with('success', 'Perintah disconnect berhasil dikirim.');
    }

    // Menghapus token Fonnte yang tersimpan (aksi "Hapus" pada tabel token)
    public function hapusToken()
    {
        $pengaturan = $this->getPengaturan();

        // Jika masih terhubung, putuskan dulu ke server Fonnte sebelum token dihapus lokal
        if ($pengaturan->fonnte_token && $pengaturan->status_koneksi === 'connect') {
            try {
                Http::withHeaders([
                    'Authorization' => $pengaturan->fonnte_token
                ])->timeout(10)->post('https://api.fonnte.com/disconnect');
            } catch (\Exception $e) {
                // Diamkan saja — token tetap dihapus dari database lokal walau request disconnect gagal
            }
        }

        // Hapus cache QR Code
        $cacheKey = 'qr_code_' . md5($pengaturan->fonnte_token);
        Cache::forget($cacheKey);

        $pengaturan->update([
            'fonnte_token'   => null,
            'status_koneksi' => 'disconnect',
            'nama_perangkat' => null,
            'nomor_wa'       => null,
            'sisa_kuota'     => 0,
            'masa_aktif'     => null,
        ]);

        return redirect()->back()->with('success', 'Token Fonnte berhasil dihapus.');
    }
}