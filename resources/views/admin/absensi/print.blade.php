<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Siswa – MA Nurul Huda Mangunsari</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 1.8cm 2cm;
            @top-left   { content: none; }
            @top-center { content: none; }
            @top-right  { content: none; }
            @bottom-left   { content: none; }
            @bottom-center { content: none; }
            @bottom-right  { content: none; }
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
        }

        /* ══ KOP SURAT ══════════════════════════════════════════ */
        .kop-wrapper {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 10px;
        }

        .kop-logo {
            width: 82px;
            height: 82px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-body {
            flex: 1;
            border-left: 3px solid #000;
            padding-left: 16px;
        }
        .kop-lembaga {
            font-size: 9pt;
            font-weight: normal;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            color: #000;
            margin-bottom: 2px;
        }
        .kop-nama {
            font-size: 19pt;
            font-weight: 900;
            letter-spacing: .4px;
            color: #000;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 2px;
        }
        .kop-sub {
            font-size: 9pt;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
            letter-spacing: .3px;
        }
        .kop-alamat {
            font-size: 8.8pt;
            color: #333;
            line-height: 1.55;
            border-top: 1px solid #999;
            padding-top: 4px;
        }

        .kop-rule {
            margin: 8px 0 0;
            border: none;
            border-top: 3px solid #000;
            box-shadow: 0 2px 0 0 #aaa;
        }

        /* ══ JUDUL DOKUMEN ══════════════════════════════════════ */
        .doc-header {
            text-align: center;
            padding: 11px 0 9px;
            border-bottom: 1.5px solid #000;
            margin-bottom: 13px;
        }
        .doc-title {
            font-size: 13.5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3.5px;
            color: #000;
            margin-bottom: 3px;
        }
        .doc-subtitle {
            font-size: 9pt;
            color: #444;
            letter-spacing: .4px;
        }

        /* ══ META INFO BOX ══════════════════════════════════════ */
        .meta-box {
            display: flex;
            margin-bottom: 14px;
            border: 1px solid #aaa;
            border-left: 4px solid #000;
            border-radius: 2px;
            background: #f8f8f8;
            overflow: hidden;
        }
        .meta-content {
            flex: 1;
            padding: 8px 14px;
        }
        .meta-content table { border: none; width: 100%; }
        .meta-content td {
            border: none;
            padding: 2px 6px 2px 0;
            vertical-align: top;
            font-size: 10pt;
        }
        .meta-content .label {
            font-weight: bold;
            color: #000;
            white-space: nowrap;
            width: 1%;
        }

        /* ══ TABEL UTAMA ════════════════════════════════════════ */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 10px;
        }

        .main-table thead tr.th-top {
            background: #000;
        }
        .main-table thead tr.th-top th {
            color: #fff;
            border: 1px solid #333;
            padding: 7px 5px;
            text-align: center;
            font-weight: 700;
            font-size: 9.5pt;
            letter-spacing: .4px;
        }

        .main-table tbody td {
            border: 1px solid #bbb;
            padding: 5px 6px;
            text-align: center;
            vertical-align: middle;
        }
        .main-table tbody tr:nth-child(even) { background: #f2f2f2; }
        .main-table tbody tr:nth-child(odd)  { background: #ffffff; }

        .col-nama { text-align: left !important; padding-left: 9px !important; }
        .col-mono { font-family: 'Courier New', monospace; font-size: 8.8pt; }

        /* Status chips — hitam putih, dibedakan dengan border */
        .chip {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 2px;
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: .2px;
            white-space: nowrap;
            border: 1px solid #000;
            background: #fff;
            color: #000;
        }
        /* Bedakan chip dengan pola background berbeda */
        .chip-hadir              { background: #fff;  border: 1.5px solid #000; }
        .chip-terlambat          { background: #ddd;  border: 1px solid #000; }
        .chip-pulang             { background: #f0f0f0; border: 1px dashed #000; }
        .chip-pulang-tepat-waktu { background: #fff;  border: 1.5px solid #000; }
        .chip-pulang-terlambat   { background: #ddd;  border: 1px solid #000; }
        .chip-pulang-lebih-awal  { background: #f0f0f0; border: 1px dashed #000; }
        .chip-belum-pulang       { background: #bbb;  border: 1px solid #000; font-style: italic; }
        .chip-izin               { background: #e8e8e8; border: 1px dotted #000; }
        .chip-sakit              { background: #e8e8e8; border: 1px dotted #000; font-style: italic; }
        .chip-alpha              { background: #bbb;  border: 1px solid #000; font-style: italic; }
        .chip-default            { background: #eee;  border: 1px solid #888; color: #444; }

        .jenis-masuk  { font-weight: 700; color: #000; }
        .jenis-pulang { font-weight: 700; color: #000; font-style: italic; }

        .empty-row td {
            font-style: italic;
            color: #666;
            padding: 20px;
            text-align: center;
        }

        /* ══ RINGKASAN ══════════════════════════════════════════ */
        .ringkas-wrap { margin-top: 14px; }

        .section-label {
            font-size: 9pt;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 7px;
            padding-left: 9px;
            border-left: 4px solid #000;
            line-height: 1.3;
        }

        .ringkas-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        .ringkas-table th {
            background: #000;
            color: #fff;
            font-size: 9pt;
            letter-spacing: .3px;
            padding: 6px 10px;
            text-align: center;
            border: 1px solid #333;
        }
        .ringkas-table td {
            background: #f8f8f8;
            border: 1px solid #bbb;
            padding: 6px 10px;
            text-align: center;
            color: #000;
        }
        .ringkas-table td.total-col {
            background: #e0e0e0;
            font-weight: 900;
            font-size: 10.5pt;
        }

        /* ══ TANDA TANGAN ═══════════════════════════════════════ */
        .ttd-section {
            margin-top: 46px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-size: 10.5pt;
        }
        .ttd-box { text-align: center; }
        .ttd-box .ttd-title { margin-bottom: 60px; line-height: 1.65; }
        .ttd-box .ttd-name {
            font-weight: 900;
            text-decoration: underline;
            font-size: 11pt;
        }
        .ttd-box .ttd-nip { font-size: 9pt; color: #444; margin-top: 3px; }

        .cap-note {
            font-size: 8pt;
            color: #888;
            text-align: center;
            margin-top: 30px;
            border-top: 1px dashed #aaa;
            padding-top: 6px;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- ══════════════ KOP SURAT ══════════════ -->
    <div class="kop-wrapper">
        <img class="kop-logo"
             src="{{ asset('image/logo.png') }}"
             alt="Logo MA Nurul Huda"
             onerror="this.style.display='none'">
        <div class="kop-body">
            <p class="kop-lembaga">Yayasan Nurul Huda Mangunsari</p>
            <h1 class="kop-nama">Madrasah Aliyah Nurul Huda</h1>
            <p class="kop-sub">Terakreditasi &mdash; NSM / NPSN</p>
            <p class="kop-alamat">
                Tj. Sari, Mangunsari, Tekung, Kabupaten Lumajang, Jawa Timur 67381
                &nbsp;&bull;&nbsp; Telp. 082244871838
                &nbsp;&bull;&nbsp; Email: manuruhlhuda@gmail.com
            </p>
        </div>
    </div>

    <hr class="kop-rule">

    <!-- ══════════════ JUDUL ══════════════════ -->
    <div class="doc-header">
        <div class="doc-title">Rekap Absensi Siswa</div>
        <div class="doc-subtitle">Madrasah Aliyah Nurul Huda Mangunsari &mdash; Tekung, Lumajang</div>
    </div>

    <!-- ══════════════ META INFO ══════════════ -->
    <div class="meta-box">
        <div class="meta-content">
            <table>
                <tr>
                    <td class="label">Periode</td>
                    <td>: {{ $tanggalMulai }} s/d {{ $tanggalSelesai }}</td>
                    <td class="label" style="padding-left:28px;">Jumlah Data</td>
                    <td>: {{ count($absensi) }} record</td>
                </tr>
                <tr>
                    <td class="label">Dicetak</td>
                    <td>: {{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
                    <td class="label" style="padding-left:28px;">Tahun Pelajaran</td>
                    <td>: {{ now()->year }}/{{ now()->year + 1 }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ══════════════ TABEL ABSENSI ═════════ -->
    <table class="main-table">
        <thead>
            <tr class="th-top">
                <th style="width:4%;">No</th>
                <th style="width:12%;">RFID</th>
                <th style="width:28%;">Nama Siswa</th>
                <th style="width:11%;">Kelas</th>
                <th style="width:10%;">Jenis</th>
                <th style="width:16%;">Status</th>
                <th style="width:13%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensi as $i => $a)
                @php
                    $status = strtolower(str_replace(' ', '-', $a->status ?? ''));
                    $chipClass = match($status) {
                        'hadir'                                    => 'chip-hadir',
                        'terlambat'                                => 'chip-terlambat',
                        'pulang'                                   => 'chip-pulang',
                        'pulang_tepat_waktu','pulang-tepat-waktu'  => 'chip-pulang-tepat-waktu',
                        'pulang_terlambat','pulang-terlambat'      => 'chip-pulang-terlambat',
                        'pulang_lebih_awal','pulang-lebih-awal'    => 'chip-pulang-lebih-awal',
                        'belum_pulang','belum-pulang'              => 'chip-belum-pulang',
                        'izin'                                     => 'chip-izin',
                        'sakit'                                    => 'chip-sakit',
                        'alpha'                                    => 'chip-alpha',
                        default                                    => 'chip-default',
                    };
                    $statusLabel = ucwords(str_replace('_', ' ', $a->status ?? '-'));
                    $jenisClass  = strtolower($a->jenis ?? '') === 'pulang' ? 'jenis-pulang' : 'jenis-masuk';
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="col-mono">{{ $a->rfid ?? '-' }}</td>
                    <td class="col-nama">{{ $a->siswa->nama ?? '-' }}</td>
                    <td>{{ $a->siswa->kelas->nama ?? '-' }}</td>
                    <td class="{{ $jenisClass }}">{{ ucfirst($a->jenis ?? '-') }}</td>
                    <td><span class="chip {{ $chipClass }}">{{ $statusLabel }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="7">Tidak ada data absensi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ══════════════ RINGKASAN ══════════════ -->
    @if(count($absensi) > 0)
    @php
        $rHadir  = collect($absensi)->filter(fn($a) => in_array(strtolower($a->status ?? ''), ['hadir','pulang_tepat_waktu','pulang tepat waktu']))->count();
        $rTlmbt  = collect($absensi)->filter(fn($a) => in_array(strtolower($a->status ?? ''), ['terlambat','pulang_terlambat','pulang terlambat']))->count();
        $rIzin   = collect($absensi)->filter(fn($a) => strtolower($a->status ?? '') === 'izin')->count();
        $rSakit  = collect($absensi)->filter(fn($a) => strtolower($a->status ?? '') === 'sakit')->count();
        $rAlpha  = collect($absensi)->filter(fn($a) => in_array(strtolower($a->status ?? ''), ['alpha','belum_pulang']))->count();
    @endphp
    <div class="ringkas-wrap">
        <div class="section-label">Ringkasan Status Kehadiran</div>
        <table class="ringkas-table">
            <thead>
                <tr>
                    <th>Hadir / Tepat Waktu</th>
                    <th>Terlambat</th>
                    <th>Izin</th>
                    <th>Sakit</th>
                    <th>Alpha / Belum Pulang</th>
                    <th>Total Seluruh</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{{ $rHadir }}</strong></td>
                    <td><strong>{{ $rTlmbt }}</strong></td>
                    <td><strong>{{ $rIzin }}</strong></td>
                    <td><strong>{{ $rSakit }}</strong></td>
                    <td><strong>{{ $rAlpha }}</strong></td>
                    <td class="total-col">{{ count($absensi) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <!-- ══════════════ TANDA TANGAN ══════════════ -->
    <div class="ttd-section">
        <div class="ttd-box">
            <div class="ttd-title">Mengetahui,<br>Kepala Madrasah</div>
            <div class="ttd-name">____________________</div>
            <div class="ttd-nip">NIP. _______________</div>
        </div>
        <div class="ttd-box">
            <div class="ttd-title">
                Lumajang, {{ now()->translatedFormat('d F Y') }}<br>
                Petugas Absensi
            </div>
            <div class="ttd-name">____________________</div>
            <div class="ttd-nip">NIP. _______________</div>
        </div>
    </div>

    <div class="cap-note">
        Dokumen ini dicetak secara otomatis oleh Sistem Informasi Absensi MA Nurul Huda Mangunsari &mdash; {{ now()->format('d/m/Y H:i') }} WIB
    </div>

</body>
</html>