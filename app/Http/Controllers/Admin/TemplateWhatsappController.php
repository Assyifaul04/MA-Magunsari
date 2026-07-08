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
            'jenis'         => 'required|in:rekap_harian',
            'isi_pesan'     => 'required|string',
        ]);

        TemplateWhatsapp::create([
            'nama_template' => $request->nama_template,
            'jenis'         => $request->jenis,
            'isi_pesan'     => $request->isi_pesan,
            'is_active'     => $request->has('is_active'),
        ]);

        return redirect()->route('templatewa.index')->with('success', 'Template pesan berhasil ditambahkan!');
    }

    public function edit(TemplateWhatsapp $template)
    {
        return response()->json([
            'success' => true,
            'data'    => $template
        ]);
    }

    public function update(Request $request, TemplateWhatsapp $template)
    {
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'jenis'         => 'required|in:rekap_harian',
            'isi_pesan'     => 'required|string',
        ]);

        $template->update([
            'nama_template' => $request->nama_template,
            'jenis'         => $request->jenis,
            'isi_pesan'     => $request->isi_pesan,
            'is_active'     => $request->has('is_active'),
        ]);

        return redirect()->route('templatewa.index')->with('success', 'Template pesan berhasil diperbarui!');
    }

    public function destroy(TemplateWhatsapp $template)
    {
        $template->delete();
        return back()->with('success', 'Template pesan berhasil dihapus!');
    }

    public function generateDefault()
    {
        $isiDefault = "📢 *NOTIFIKASI ABSENSI SISWA*\n\n*Assalamu'alaikum Warahmatullahi Wabarakatuh.*\n\nYth. Bapak/Ibu Orang Tua/Wali Siswa,\n\nDengan hormat, kami informasikan bahwa putra/putri Bapak/Ibu telah melakukan absensi pada hari ini dengan rincian sebagai berikut:\n\n👤 *Nama Siswa* : {nama_siswa}\n🏫 *Kelas* : {kelas}\n📅 *Tanggal* : {tanggal}\n\n🟢 *Jam Masuk* : {jam_masuk}\n🔴 *Jam Pulang* : {jam_pulang}\n\nTerima kasih atas perhatian dan kerja sama Bapak/Ibu dalam mendukung kedisiplinan putra/putrinya.\n\n*Wassalamu'alaikum Warahmatullahi Wabarakatuh.*\n\nHormat kami,\n\n*MA Nurul Huda*";

        $sudahAda = TemplateWhatsapp::where('jenis', 'rekap_harian')->exists();

        if (!$sudahAda) {
            TemplateWhatsapp::create([
                'nama_template' => 'Template Rekap Harian',
                'jenis'         => 'rekap_harian',
                'isi_pesan'     => $isiDefault,
                'is_active'     => true,
            ]);
            
            return back()->with('success', "Template Default berhasil dibuat otomatis!");
        }

        return back()->with('info', "Template sudah ada, tidak ada yang perlu di-generate ulang.");
    }
}