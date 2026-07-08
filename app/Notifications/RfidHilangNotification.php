<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RfidHilangNotification extends Notification
{
    use Queueable;

    protected $siswa;

    public function __construct($siswa)
    {
        $this->siswa = $siswa;
    }

    public function via($notifiable)
    {
        return ['database']; // Menyimpan notifikasi ke dalam tabel database
    }

    public function toDatabase($notifiable)
    {
        return [
            'siswa_id'   => $this->siswa->id,
            'nama_siswa' => $this->siswa->nama,
            'nama_kelas' => $this->siswa->kelas->nama ?? '-',
            'pesan'      => 'dilaporkan hilang dan telah dinonaktifkan.',
            'icon'       => 'bi-shield-exclamation',
            'color'      => 'text-danger'
        ];
    }
}