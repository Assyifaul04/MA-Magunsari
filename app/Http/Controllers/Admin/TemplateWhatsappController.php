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
        // Siapkan kata-kata default untuk masing-masing jenis tanpa ikon
        $defaults = [
            'masuk' => [
                'nama_template' => 'Default Masuk Pagi',
                'isi_pesan' => "*INFORMASI KEHADIRAN SISWA*\n*Sekolah Nurul Huda*\n\nAssalamu'alaikum Wr. Wb.\nYth. Bapak/Ibu Wali Murid,\n\nBerikut adalah informasi kehadiran putra/putri Anda pada hari ini:\n\n*Nama* : {nama_siswa}\n*Kelas* : {kelas}\n*Tanggal* : {tanggal}\n*Jam* : {jam} WIB\n*Status* : *{status}*\n\nAlhamdulillah, ananda telah tiba di sekolah dengan selamat. Semoga mendapatkan ilmu yang bermanfaat hari ini.\n\nTerima kasih atas perhatian dan kerjasamanya.\nWassalamu'alaikum Wr. Wb.",
            ],
            'terlambat' => [
                'nama_template' => 'Default Terlambat',
                'isi_pesan' => "*INFORMASI KEHADIRAN SISWA*\n*Sekolah Nurul Huda*\n\nAssalamu'alaikum Wr. Wb.\nYth. Bapak/Ibu Wali Murid,\n\nBerikut adalah informasi kehadiran putra/putri Anda pada hari ini:\n\n*Nama* : {nama_siswa}\n*Kelas* : {kelas}\n*Tanggal* : {tanggal}\n*Jam* : {jam} WIB\n*Status* : *{status}*\n\nAnanda telah berada di sekolah, namun tercatat datang melewati batas waktu yang ditentukan. Mohon bantuan Bapak/Ibu untuk memotivasi ananda agar dapat berangkat lebih awal ke depannya.\n\nTerima kasih atas perhatian dan kerjasamanya.\nWassalamu'alaikum Wr. Wb.",
            ],
            'pulang' => [
                'nama_template' => 'Default Pulang',
                'isi_pesan' => "*INFORMASI KEHADIRAN SISWA*\n*Sekolah Nurul Huda*\n\nAssalamu'alaikum Wr. Wb.\nYth. Bapak/Ibu Wali Murid,\n\nBerikut adalah informasi kepulangan putra/putri Anda:\n\n*Nama* : {nama_siswa}\n*Kelas* : {kelas}\n*Tanggal* : {tanggal}\n*Jam* : {jam} WIB\n*Status* : *{status}*\n\nAlhamdulillah, kegiatan belajar mengajar hari ini telah selesai dan ananda telah melakukan absensi pulang.\n\nTerima kasih atas perhatiannya.\nWassalamu'alaikum Wr. Wb.",
            ],
            'izin' => [
                'nama_template' => 'Default Izin',
                'isi_pesan' => "*INFORMASI KEHADIRAN SISWA*\n*Sekolah Nurul Huda*\n\nAssalamu'alaikum Wr. Wb.\nYth. Bapak/Ibu Wali Murid,\n\nBerikut adalah pencatatan absensi putra/putri Anda pada hari ini:\n\n*Nama* : {nama_siswa}\n*Kelas* : {kelas}\n*Tanggal* : {tanggal}\n*Status* : *{status}*\n\nData izin ananda telah kami konfirmasi dan catat dalam sistem akademik sekolah.\n\nTerima kasih atas informasinya.\nWassalamu'alaikum Wr. Wb.",
            ],
            'sakit' => [
                'nama_template' => 'Default Sakit',
                'isi_pesan' => "*INFORMASI KEHADIRAN SISWA*\n*Sekolah Nurul Huda*\n\nAssalamu'alaikum Wr. Wb.\nYth. Bapak/Ibu Wali Murid,\n\nBerikut adalah pencatatan absensi putra/putri Anda pada hari ini:\n\n*Nama* : {nama_siswa}\n*Kelas* : {kelas}\n*Tanggal* : {tanggal}\n*Status* : *{status}*\n\nKami telah menerima informasi terkait kondisi ananda. Mari kita doakan bersama agar ananda lekas diberikan kesembuhan dan dapat kembali beraktivitas di sekolah seperti sedia kala.\n\nTerima kasih.\nWassalamu'alaikum Wr. Wb.",
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