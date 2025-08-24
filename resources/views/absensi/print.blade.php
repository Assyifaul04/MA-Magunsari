<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Siswa</title>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        body { font-family: 'Times New Roman', serif; font-size: 12pt; margin: 0; padding: 0; }
        .kop { display: flex; align-items: center; margin-bottom: 5px; }
        .kop img { width: 90px; margin-right: 15px; }
        .kop .info { flex: 1; text-align: center; }
        .kop .info h1 { font-size: 20pt; margin: 0; font-weight: bold; text-transform: uppercase; }
        .kop .info p { margin: 2px 0; font-size: 11pt; }
        .line { border-top: 2px solid #000; border-bottom: 4px double #000; margin-bottom: 20px; }
        .title { text-align: center; font-size: 14pt; font-weight: bold; margin: 15px 0; text-decoration: underline; }
        .meta { font-size: 11pt; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; font-size: 10.5pt; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: center; }
        th { background-color: #f2f2f2 !important; }
        tr:nth-child(even) { background-color: #fafafa; }
        .footer { margin-top: 50px; text-align: right; font-size: 11pt; }
        .footer .date { margin-bottom: 70px; }
        .footer .signature { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body onload="window.print()">

    <!-- Kop Sekolah -->
    <div class="kop">
        <img src="{{ asset('image/logo.png') }}" alt="Logo Sekolah" onerror="this.src='https://via.placeholder.com/90?text=LOGO';">
        <div class="info">
            <h1>Madrasah Aliyah</h1>
            <p>Desa Mangunsari, Kecamatan Tekung</p>
            <p>Kabupaten Lumajang, Provinsi Jawa Timur</p>
        </div>
    </div>
    <div class="line"></div>

    <!-- Judul -->
    <div class="title">REKAP ABSENSI SISWA</div>

    <!-- Meta -->
    <div class="meta">
        <strong>Periode:</strong> 
        {{ $tanggalMulai }} s/d {{ $tanggalSelesai }} |
        <strong>Dicetak:</strong> {{ now()->format('d-m-Y H:i') }}
    </div>

    <!-- Tabel -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>RFID</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Jam</th>
            </tr>
        </thead>
        <tbody>
            {{-- Absensi --}}
            @foreach($absensi as $i => $a)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $a->rfid }}</td>
                    <td>{{ $a->siswa->nama }}</td>
                    <td>{{ $a->siswa->kelas->nama ?? '-' }}</td>
                    <td>{{ ucfirst($a->jenis) }}</td>
                    <td>
                        <span style="color:
                            @if($a->status == 'hadir') green
                            @elseif($a->status == 'terlambat') orange
                            @elseif($a->status == 'pulang') blue
                            @else red
                            @endif">
                            {{ ucfirst($a->status) }}
                        </span>
                    </td>
                    <td>{{ $a->tanggal }}</td>
                    <td>{{ $a->jam }}</td>
                </tr>
            @endforeach

            {{-- Tidak hadir --}}
            @foreach($siswaTidakHadir as $i => $s)
                <tr>
                    <td>{{ $i + 1 + $absensi->count() }}</td>
                    <td>{{ $s->rfid }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->kelas->nama ?? '-' }}</td>
                    <td>-</td>
                    <td style="color: red;">Tidak Hadir</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            @endforeach

            @if($absensi->isEmpty() && $siswaTidakHadir->isEmpty())
                <tr>
                    <td colspan="8" style="font-style: italic;">Tidak ada data absensi pada periode ini.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="date">Lumajang, {{ now()->translatedFormat('d F Y') }}</div>
        <div class="signature">Petugas Absensi</div>
    </div>

</body>
</html>
