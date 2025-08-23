<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Siswa</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }
        body {
            font-family: 'Times New Roman', serif;
            color: #000;
            line-height: 1.5;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }
        * { box-sizing: border-box; }

        /* === KOP SEKOLAH === */
        .kop {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        .kop img {
            width: 90px;
            height: auto;
            margin-right: 15px;
        }
        .kop .info {
            flex: 1;
            text-align: center;
        }
        .kop .info h1 {
            font-size: 20pt;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop .info h2 {
            font-size: 14pt;
            margin: 2px 0;
            font-weight: normal;
        }
        .kop .info p {
            margin: 2px 0;
            font-size: 11pt;
        }
        .line {
            border-top: 2px solid #000;
            border-bottom: 4px double #000;
            margin-bottom: 20px;
        }

        /* === JUDUL === */
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin: 15px 0;
            text-decoration: underline;
        }

        /* === META === */
        .meta {
            font-size: 11pt;
            margin-bottom: 15px;
        }

        /* === TABEL === */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        th {
            background-color: #f2f2f2 !important;
            font-weight: bold;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        td { vertical-align: middle; }

        /* === FOOTER === */
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 11pt;
        }
        .footer .date {
            margin-bottom: 70px;
        }
        .footer .signature {
            font-weight: bold;
            text-decoration: underline;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Kop Sekolah -->
    <div class="kop">
        <img src="{{ asset('image/logo.png') }}" alt="Logo Sekolah"
             onerror="this.src='https://via.placeholder.com/90?text=LOGO';">
        <div class="info">
            <h1>Madrasah Aliyah</h1>
            <p>Desa Mangunsari, Kecamatan Tekung</p>
            <p>Kabupaten Lumajang, Provinsi Jawa Timur</p>
        </div>
    </div>
    <div class="line"></div>

    <!-- Judul Laporan -->
    <div class="title">
        REKAP ABSENSI SISWA
    </div>

    <!-- Informasi Tanggal -->
    <div class="meta">
        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($tanggal ?? now())->translatedFormat('d F Y') }} |
        <strong>Dicetak pada:</strong> {{ now()->format('d-m-Y H:i') }}
    </div>

    <!-- Tabel Absensi -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>RFID</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Jam</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensi as $i => $a)
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td>{{ $a->rfid }}</td>
                    <td>{{ $a->siswa->nama }}</td>
                    <td>{{ $a->siswa->kelas->nama ?? '-' }}</td>
                    <td style="text-align: center;">{{ ucfirst($a->jenis) }}</td>
                    <td style="text-align: center;">
                        <strong style="color:
                            @if($a->status == 'hadir') green
                            @elseif($a->status == 'terlambat') orange
                            @elseif($a->status == 'pulang') blue
                            @else black
                            @endif">
                            {{ ucfirst($a->status) }}
                        </strong>
                    </td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($a->jam)->format('H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; font-style: italic;">
                        Tidak ada data absensi untuk tanggal ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer Tanda Tangan -->
    <div class="footer">
        <div class="date">Lumajang, {{ now()->translatedFormat('d F Y') }}</div>
        <div class="signature">Petugas Absensi</div>
    </div>

</body>
</html>
