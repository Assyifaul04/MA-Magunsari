<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplateWhatsapp;
use Illuminate\Http\Request;

class TemplateWhatsappController extends Controller
{
    public function index()
    {
        $templates = TemplateWhatsapp::latest()->get();
        return view('admin.templatewa.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'jenis'         => 'required|in:masuk,pulang,izin,sakit,terlambat',
            'isi_pesan'     => 'required|string',
        ]);

        TemplateWhatsapp::create([
            'nama_template' => $request->nama_template,
            'jenis'         => $request->jenis,
            'isi_pesan'     => $request->isi_pesan,
            'is_active'     => $request->has('is_active'), // True jika dicentang
        ]);

        return redirect()
            ->route('templatewa.index')
            ->with('success', 'Template pesan WhatsApp berhasil ditambahkan!');
    }

    public function edit(TemplateWhatsapp $template)
    {
        // Mengirim data ke AJAX Modal
        return response()->json([
            'success' => true,
            'data'    => $template
        ]);
    }

    public function update(Request $request, TemplateWhatsapp $template)
    {
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'jenis'         => 'required|in:masuk,pulang,izin,sakit,terlambat',
            'isi_pesan'     => 'required|string',
        ]);

        $template->update([
            'nama_template' => $request->nama_template,
            'jenis'         => $request->jenis,
            'isi_pesan'     => $request->isi_pesan,
            'is_active'     => $request->has('is_active'),
        ]);

        return redirect()
            ->route('templatewa.index')
            ->with('success', 'Template pesan WhatsApp berhasil diperbarui!');
    }

    public function destroy(TemplateWhatsapp $template)
    {
        $template->delete();

        return back()->with('success', 'Template pesan WhatsApp berhasil dihapus!');
    }

    public function generateDefault()
    {
        // Siapkan kata-kata default untuk masing-masing jenis
        $defaults = [
            'masuk' => [
                'nama_template' => 'Default Masuk Pagi',
                'isi_pesan' => "Pemberitahuan Akademik:\n\nDiberitahukan kepada Bapak/Ibu orang tua dari *{nama_siswa}*, bahwa ananda hari ini telah tiba dan melakukan absen *{status}* di sekolah pada pukul *{jam}*.\n\nTerima kasih atas perhatiannya.",
            ],
            'terlambat' => [
                'nama_template' => 'Default Terlambat',
                'isi_pesan' => "Pemberitahuan Akademik:\n\nMohon maaf Bapak/Ibu, ananda *{nama_siswa}* tiba di sekolah pada pukul *{jam}*, berstatus *{status}* (melewati batas jam masuk yang ditentukan).\n\nTerima kasih atas perhatiannya.",
            ],
            'pulang' => [
                'nama_template' => 'Default Pulang',
                'isi_pesan' => "Pemberitahuan Akademik:\n\nDiberitahukan kepada Bapak/Ibu, pembelajaran hari ini telah usai. Ananda *{nama_siswa}* telah melakukan absen *{status}* pada pukul *{jam}*.\n\nTerima kasih.",
            ],
            'izin' => [
                'nama_template' => 'Default Izin',
                'isi_pesan' => "Pemberitahuan Akademik:\n\nBerdasarkan konfirmasi, ananda *{nama_siswa}* (Kelas {kelas}) pada tanggal *{tanggal}* tercatat *{status}*.\n\nTerima kasih atas informasinya.",
            ],
            'sakit' => [
                'nama_template' => 'Default Sakit',
                'isi_pesan' => "Pemberitahuan Akademik:\n\nKami telah mencatat bahwa ananda *{nama_siswa}* berhalangan hadir pada tanggal *{tanggal}* dikarenakan *{status}*.\n\nMari kita doakan semoga ananda lekas sembuh.",
            ],
        ];

        $dibuat = 0;

        foreach ($defaults as $jenis => $data) {
            // Cek apakah template untuk jenis ini sudah ada?
            $sudahAda = TemplateWhatsapp::where('jenis', $jenis)->exists();

            // Jika belum ada, baru kita buatkan otomatis
            if (!$sudahAda) {
                TemplateWhatsapp::create([
                    'nama_template' => $data['nama_template'],
                    'jenis'         => $jenis,
                    'isi_pesan'     => $data['isi_pesan'],
                    'is_active'     => true,
                ]);
                $dibuat++;
            }
        }

        if ($dibuat > 0) {
            return back()->with('success', "$dibuat Template Default berhasil dibuat otomatis!");
        }

        return back()->with('info', "Semua jenis template sudah ada, tidak ada yang perlu di-generate ulang.");
    }
}
