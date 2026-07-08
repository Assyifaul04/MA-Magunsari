@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    :root {
        --brand-primary:       #2f9e44;
        --brand-primary-light: #e6f7ec;
        --brand-primary-dark:  #1e7e34;
        --brand-primary-soft:  #d3f9d8;
        --brand-danger:        #e03131;
        --brand-danger-light:  #fff5f5;
        --brand-info:          #0891b2;
        --brand-info-light:    #e0f2fe;
        --brand-gold:          #e08e00;
        --brand-gold-light:    #fff9db;
        --surface:             #ffffff;
        --surface-soft:        #f6faf7;
        --surface-border:      #e1ede3;
        --text-primary:        #1a1d23;
        --text-secondary:      #6c757d;
        --text-muted:          #adb5bd;
        --shadow-sm:  0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md:  0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.05);
        --radius-sm:  6px;
        --radius-md:  10px;
        --radius-xl:  20px;
    }
    body, .card { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ══════════════ HERO ══════════════ */
    .page-hero {
        background: linear-gradient(135deg, #0b3d24 0%, #1e7e34 55%, #40c463 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 8px 32px rgba(30,126,52,.28);
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute;
        right: -50px;
        top: -50px;
        width: 210px;
        height: 210px;
        background: rgba(255,255,255,.08);
        border-radius: 50%;
    }
    .page-hero::after {
        content: '';
        position: absolute;
        right: 80px;
        bottom: -70px;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,.06);
        border-radius: 50%;
    }
    .page-hero-left h1 {
        font-size: 1.45rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 4px;
    }
    .page-hero-left p {
        color: rgba(255,255,255,.82);
        margin: 0;
        font-size: .85rem;
    }

    .alert-pro {
        border: none;
        border-radius: var(--radius-md);
        padding: 14px 18px;
        font-size: .875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 16px;
    }
    .alert-pro-success {
        background: var(--brand-primary-light);
        color: var(--brand-primary-dark);
    }
    .alert-pro-danger {
        background: var(--brand-danger-light);
        color: #c92a2a;
    }
    .alert-pro .btn-close {
        margin-left: auto;
    }

    /* ══════════════ CARD ══════════════ */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        height: 100%;
    }
    .data-card-header {
        padding: 16px 22px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }
    .header-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-md);
        display: grid;
        place-items: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }
    .header-icon-green {
        background: var(--brand-primary-soft);
        color: var(--brand-primary-dark);
    }
    .header-icon-dark {
        background: #e9ecef;
        color: #343a40;
    }
    .data-card-title {
        font-size: .95rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }
    .data-card-subtitle {
        font-size: .72rem;
        color: var(--text-muted);
        margin: 0;
    }
    .data-card-body {
        padding: 24px;
    }

    /* ══════════════ FORM ══════════════ */
    .form-label-pro {
        font-size: .82rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 6px;
        display: block;
    }
    .form-control-pro {
        width: 100%;
        padding: 11px 16px;
        border: 1.5px solid var(--surface-border);
        border-radius: var(--radius-md);
        font-size: .85rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-primary);
        background: var(--surface-soft);
        transition: all .2s;
    }
    .form-control-pro:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(47,158,68,.13);
        outline: none;
        background: #fff;
    }
    .form-hint {
        font-size: .75rem;
        color: var(--text-muted);
        margin-top: 6px;
    }

    .btn-pro {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        font-size: .85rem;
        border-radius: 50px;
        padding: 11px 20px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all .2s ease;
        cursor: pointer;
        width: 100%;
    }
    .btn-pro-primary {
        background: var(--brand-primary);
        color: #fff;
        box-shadow: 0 4px 12px rgba(47,158,68,.28);
    }
    .btn-pro-primary:hover {
        background: var(--brand-primary-dark);
        color: #fff;
        transform: translateY(-1px);
    }
    .btn-pro-outline-danger {
        background: transparent;
        border: 1.5px solid #ffc9c9;
        color: var(--brand-danger);
    }
    .btn-pro-outline-danger:hover {
        background: var(--brand-danger);
        border-color: var(--brand-danger);
        color: #fff;
    }
    .btn-pro-secondary {
        background: var(--surface-soft);
        border: 1.5px solid var(--surface-border);
        color: var(--text-secondary);
    }
    .btn-pro-secondary:hover {
        background: #eef2ef;
        color: var(--text-primary);
    }

    /* ══════════════ TOKEN TABLE ══════════════ */
    .section-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 22px 0 14px;
    }
    .section-divider span {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--text-muted);
        white-space: nowrap;
    }
    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--surface-border);
    }

    .token-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-md);
        overflow: hidden;
    }
    .token-table thead th {
        background: var(--surface-soft);
        color: var(--text-secondary);
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        padding: 10px 14px;
        border-bottom: 1px solid var(--surface-border);
    }
    .token-table tbody td {
        padding: 13px 14px;
        font-size: .83rem;
        color: var(--text-primary);
        vertical-align: middle;
    }
    .token-chip {
        font-family: monospace;
        font-size: .8rem;
        font-weight: 600;
        background: var(--surface-soft);
        color: var(--text-secondary);
        padding: 5px 12px;
        border-radius: 50px;
        border: 1px solid var(--surface-border);
        letter-spacing: .02em;
        display: inline-block;
    }

    .token-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
        flex-shrink: 0;
    }
    .token-status-dot.online {
        background: var(--brand-primary);
        box-shadow: 0 0 0 3px var(--brand-primary-light);
    }
    .token-status-dot.offline {
        background: var(--brand-danger);
        box-shadow: 0 0 0 3px var(--brand-danger-light);
    }

    .action-icons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        border: 1.5px solid;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: transparent;
        transition: all .2s;
        font-size: .85rem;
        padding: 0;
        flex-shrink: 0;
    }
    .btn-icon-info {
        border-color: #a5f3fc;
        color: var(--brand-info);
        background: var(--brand-info-light);
    }
    .btn-icon-info:hover {
        background: var(--brand-info);
        border-color: var(--brand-info);
        color: #fff;
    }
    .btn-icon-danger {
        border-color: #ffc9c9;
        color: var(--brand-danger);
        background: var(--brand-danger-light);
    }
    .btn-icon-danger:hover {
        background: var(--brand-danger);
        border-color: var(--brand-danger);
        color: #fff;
    }

    .token-empty {
        text-align: center;
        padding: 28px 14px;
        color: var(--text-muted);
        font-size: .82rem;
    }
    .token-empty i {
        font-size: 1.6rem;
        display: block;
        margin-bottom: 8px;
        color: var(--text-muted);
    }

    /* ══════════════ STATUS ══════════════ */
    .status-block {
        text-align: center;
    }
    .status-icon-wrap {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        margin: 0 auto 14px;
        font-size: 1.8rem;
    }
    .status-icon-warning {
        background: var(--brand-gold-light);
        color: var(--brand-gold);
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 22px;
        border-radius: 50px;
        font-size: .8rem;
        font-weight: 700;
        letter-spacing: .03em;
    }
    .status-pill-online {
        background: var(--brand-primary-light);
        color: var(--brand-primary-dark);
    }
    .status-pill-offline {
        background: var(--brand-danger-light);
        color: #c92a2a;
    }
    .status-pill .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
    }
    .status-pill-online .dot {
        animation: pulse-dot 1.6s infinite;
    }
    @keyframes pulse-dot {
        0%,100% { opacity: 1; }
        50% { opacity: .35; }
    }

    .device-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        text-align: left;
    }
    .device-table tr:not(:last-child) th,
    .device-table tr:not(:last-child) td {
        border-bottom: 1px solid var(--surface-border);
    }
    .device-table th {
        padding: 12px 14px;
        font-size: .75rem;
        font-weight: 700;
        color: var(--text-secondary);
        background: var(--surface-soft);
        width: 42%;
        white-space: nowrap;
    }
    .device-table td {
        padding: 12px 14px;
        font-size: .85rem;
        color: var(--text-primary);
    }
    .device-table {
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-md);
        overflow: hidden;
    }

    /* QR Code & Animasi */
    .qr-box-container {
        position: relative;
        display: inline-block;
        margin-bottom: 18px;
    }
    .qr-box {
        padding: 14px;
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-md);
        background: #fff;
        box-shadow: var(--shadow-sm);
    }
    .qr-box img {
        width: 230px;
        height: 230px;
        object-fit: contain;
        border-radius: var(--radius-sm);
        transition: opacity 0.3s ease;
    }
    
    .qr-success-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        border-radius: var(--radius-md);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s ease;
        z-index: 10;
    }
    .qr-success-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .qr-success-overlay i {
        font-size: 3.5rem;
        color: var(--brand-primary);
        margin-bottom: 12px;
        transform: scale(0);
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .qr-success-overlay.active i {
        transform: scale(1);
    }
    .qr-success-overlay span {
        font-weight: 700;
        color: var(--brand-primary-dark);
        font-size: 1.1rem;
    }

    .steps-hint {
        background: var(--surface-soft);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-md);
        padding: 14px 18px;
        font-size: .78rem;
        color: var(--text-secondary);
        margin-bottom: 18px;
        text-align: left;
    }
    .steps-hint b {
        color: var(--text-primary);
    }

    .alert-waiting {
        background: var(--brand-gold-light);
        color: #946200;
        border-radius: var(--radius-md);
        padding: 14px 18px;
        font-size: .82rem;
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    /* Loading Spinner */
    .spinner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.85);
        border-radius: var(--radius-md);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 10;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    .spinner-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .spinner-overlay i {
        font-size: 2.5rem;
        color: var(--brand-primary);
        margin-bottom: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-hero {
            padding: 20px;
        }
        .page-hero-left h1 {
            font-size: 1.1rem;
        }
        .data-card-body {
            padding: 16px;
        }
        .qr-box img {
            width: 180px;
            height: 180px;
        }
        .device-table th {
            width: 35%;
        }
    }
</style>

<div class="page-hero">
    <div class="page-hero-left">
        <h1><i class="fab fa-whatsapp me-2" style="opacity:.9"></i>Pengaturan WhatsApp Gateway</h1>
        <p>Kelola token API dan koneksi perangkat WhatsApp di sini.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert-pro alert-pro-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        <span><strong>Sukses!</strong> {{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(isset($errorApi) && $errorApi)
    <div class="alert-pro alert-pro-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <span><strong>Kesalahan API!</strong> {{ $errorApi }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Bagian Kiri: Form Token + Tabel Token --}}
    <div class="col-md-5">
        <div class="data-card">
            <div class="data-card-header">
                <div class="header-icon header-icon-green"><i class="fas fa-key"></i></div>
                <div>
                    <p class="data-card-title">Konfigurasi Token Gateway</p>
                    <p class="data-card-subtitle">Hubungkan aplikasi ke layanan Gateway</p>
                </div>
            </div>
            <div class="data-card-body">

                {{-- Form untuk MENYIMPAN token --}}
                <form action="{{ route('pengaturan-wa.update') }}" method="POST" id="formToken">
                    @csrf
                    <div class="mb-3">
                        <label for="fonnte_token" class="form-label-pro">API Token :</label>
                        <input type="text"
                               name="fonnte_token"
                               id="fonnte_token"
                               class="form-control-pro"
                               value="{{ old('fonnte_token') }}"
                               placeholder="Masukkan Token Gateway Baru"
                               {{ empty($pengaturan->fonnte_token) ? 'required' : '' }}>
                        <p class="form-hint">
                            @if(!empty($pengaturan->fonnte_token))
                                Token saat ini: <strong>{{ \Illuminate\Support\Str::mask($pengaturan->fonnte_token, '•', 6) }}</strong>
                            @else
                                Token digunakan untuk menghubungkan aplikasi dengan layanan Gateway.
                            @endif
                        </p>
                    </div>
                    <button type="submit" class="btn-pro btn-pro-primary">
                        <i class="fas fa-save"></i> {{ !empty($pengaturan->fonnte_token) ? 'Perbarui Token' : 'Simpan Token' }}
                    </button>
                </form>

                {{-- Tabel Token Tersimpan --}}
                <div class="section-divider"><span>Token Tersimpan</span></div>

                <table class="token-table">
                    <thead>
                        <tr>
                            <th>Token</th>
                            <th style="text-align:center;width:110px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($pengaturan->fonnte_token))
                            <tr>
                                <td>
                                    <span class="token-status-dot {{ $pengaturan->status_koneksi === 'connect' ? 'online' : 'offline' }}"></span>
                                    <span class="token-chip">{{ \Illuminate\Support\Str::mask($pengaturan->fonnte_token, '•', 6) }}</span>
                                </td>
                                <td>
                                    <div class="action-icons">
                                        <a href="{{ route('pengaturan-wa.index') }}" class="btn-icon btn-icon-info" title="Cek Koneksi">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                        <form action="{{ route('pengaturan-wa.hapus-token') }}" method="POST" id="formHapusToken" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-icon btn-icon-danger" title="Hapus Token" onclick="return confirmHapusToken(event)">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2">
                                    <div class="token-empty">
                                        <i class="fas fa-key"></i>
                                        Belum ada token tersimpan
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- Bagian Kanan: Status Perangkat & QR Code --}}
    <div class="col-md-7">
        <div class="data-card">
            <div class="data-card-header">
                <div class="header-icon header-icon-dark"><i class="fas fa-mobile-alt"></i></div>
                <div>
                    <p class="data-card-title">Status Perangkat WhatsApp</p>
                    <p class="data-card-subtitle">Pantau koneksi &amp; kuota perangkat Anda</p>
                </div>
            </div>
            <div class="data-card-body status-block">

                @if(empty($pengaturan->fonnte_token))
                    <div class="status-icon-wrap status-icon-warning">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h6 style="font-weight:700;color:var(--text-primary)">Token Belum Diatur</h6>
                    <p class="text-muted small mb-0">Silakan masukkan Token Gateway di form sebelah kiri terlebih dahulu.</p>
                @else

                    {{-- Indikator Status Koneksi --}}
                    <div class="mb-4 pb-3" style="border-bottom:1px solid var(--surface-border)">
                        <p class="form-hint mb-2" style="font-size:.78rem">STATUS SAAT INI</p>
                        @if($pengaturan->status_koneksi === 'connect')
                            <span class="status-pill status-pill-online"><span class="dot"></span>CONNECTED</span>
                        @else
                            <span class="status-pill status-pill-offline"><span class="dot"></span>DISCONNECTED</span>
                        @endif
                    </div>

                    {{-- Tampilan Berdasarkan Status --}}
                    @if($pengaturan->status_koneksi === 'connect')

                        {{-- Info Perangkat (Jika Terhubung) --}}
                        <table class="device-table mb-3">
                            <tbody>
                                <tr>
                                    <th>Nama Perangkat</th>
                                    <td>{{ $pengaturan->nama_perangkat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor WhatsApp</th>
                                    <td style="font-weight:700;color:var(--brand-primary-dark)">{{ $pengaturan->nomor_wa ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Sisa Kuota Pesan</th>
                                    <td>{{ isset($pengaturan->sisa_kuota) ? number_format($pengaturan->sisa_kuota, 0, ',', '.') : '-' }} Pesan</td>
                                </tr>
                                <tr>
                                    <th>Masa Aktif Berakhir</th>
                                    <td>{{ $pengaturan->masa_aktif ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- Tombol Disconnect Perangkat --}}
                        <form action="{{ route('pengaturan-wa.disconnect') }}" method="POST" id="formDisconnect">
                            @csrf
                            <button type="submit" class="btn-pro btn-pro-outline-danger" onclick="return confirmDisconnect(event)">
                                <i class="fas fa-unlink"></i> Putuskan Koneksi Perangkat
                            </button>
                        </form>

                    @else

                        {{-- Area Scan QR Code (Jika Terputus) --}}
                        <h6 style="font-weight:700;color:var(--brand-primary-dark);margin-bottom:10px">Tautkan Perangkat Anda</h6>
                        <div class="steps-hint">
                            Buka aplikasi WhatsApp di HP Anda <b>&gt;</b> Ketuk ikon titik tiga (Menu) / Pengaturan <b>&gt;</b> Pilih Perangkat Tertaut <b>&gt;</b> Tautkan Perangkat.
                        </div>

                        @if(isset($qrCode) && $qrCode)
                            <div class="qr-box-container">
                                <div class="qr-box">
                                    <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code WhatsApp" id="qr-image">
                                </div>
                                {{-- Overlay Animasi Sukses --}}
                                <div class="qr-success-overlay" id="qr-success">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Berhasil Terhubung!</span>
                                </div>
                                {{-- Spinner Loading --}}
                                <div class="spinner-overlay" id="qr-loading">
                                    <i class="fas fa-circle-notch fa-spin"></i>
                                    <span style="font-weight:600;color:var(--text-primary)">Memuat ulang...</span>
                                </div>
                            </div>
                            
                            {{-- Teks indikator otomatis --}}
                            <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 5px;">
                                <i class="fas fa-circle-notch fa-spin text-primary me-1"></i> Menunggu pindaian...
                            </div>
                            
                            {{-- Tombol Refresh Manual --}}
                            <div style="margin-top: 15px;">
                                <a href="{{ route('pengaturan-wa.index') }}" class="btn-pro btn-pro-secondary" style="width:auto;padding:8px 20px;">
                                    <i class="fas fa-redo"></i> Refresh QR Code
                                </a>
                            </div>
                        @else
                            <div class="alert-waiting mb-3">
                                <i class="fas fa-spinner fa-spin"></i> Sedang meminta QR Code dari server Gateway...
                            </div>
                            <a href="{{ route('pengaturan-wa.index') }}" class="btn-pro btn-pro-secondary">
                                <i class="fas fa-redo"></i> Refresh Halaman
                            </a>
                        @endif

                    @endif

                @endif

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ═══════ Pengecekan Status Otomatis (Auto Polling) ═══════
            // Hanya jalankan jika ada QR Code dan status tidak connect
            @if(!empty($pengaturan->fonnte_token) && $pengaturan->status_koneksi !== 'connect' && isset($qrCode) && $qrCode)
                
                let pollingAttempts = 0;
                const MAX_ATTEMPTS = 20; // Maksimal 20 kali polling (60 detik)
                
                const pollingInterval = setInterval(async () => {
                    pollingAttempts++;
                    
                    // Jika sudah mencapai batas maksimal, hentikan polling
                    if (pollingAttempts > MAX_ATTEMPTS) {
                        clearInterval(pollingInterval);
                        // Tampilkan pesan untuk refresh manual
                        const statusText = document.querySelector('.status-block > div:last-child');
                        if (statusText) {
                            statusText.innerHTML = `
                                <div style="margin-top:15px;padding:12px;background:#f8f9fa;border-radius:8px;">
                                    <i class="fas fa-info-circle text-warning"></i>
                                    <span style="font-size:0.85rem;color:var(--text-secondary);">
                                        Waktu pemindaian habis. Silakan <a href="${window.location.href}" style="color:var(--brand-primary);font-weight:600;">refresh halaman</a> untuk QR Code baru.
                                    </span>
                                </div>
                            `;
                        }
                        return;
                    }
                    
                    try {
                        // Gunakan endpoint API khusus untuk cek status (bukan fetch seluruh halaman)
                        const response = await fetch('{{ route("pengaturan-wa.cek-status") }}', {
                            headers: { 
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        
                        const data = await response.json();
                        
                        // Jika status sudah connect/terhubung
                        if (data.status === 'connect') {
                            clearInterval(pollingInterval);
                            
                            // Munculkan animasi sukses
                            const qrOverlay = document.getElementById('qr-success');
                            if (qrOverlay) {
                                qrOverlay.classList.add('active');
                            }
                            
                            // Tampilkan loading
                            const loading = document.getElementById('qr-loading');
                            if (loading) {
                                loading.classList.add('active');
                            }
                            
                            // Tunggu 2 detik, lalu reload halaman
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        }
                        
                    } catch (error) {
                        // Jika error karena rate limit, hentikan polling
                        if (error.message && error.message.includes('429')) {
                            clearInterval(pollingInterval);
                            console.log('Polling dihentikan karena rate limit');
                        }
                        // Silent error untuk error lainnya
                    }
                }, 3000); // Polling setiap 3 detik

                // Bersihkan interval saat halaman akan di-unload
                window.addEventListener('beforeunload', function() {
                    if (pollingInterval) {
                        clearInterval(pollingInterval);
                    }
                });
            @endif

            // ═══════ Konfirmasi Hapus Token ═══════
            window.confirmHapusToken = function(event) {
                event.preventDefault();
                
                Swal.fire({
                    title: 'Hapus Token?',
                    html: 'Token Gateway akan <strong>dihapus permanen</strong> dan perangkat WhatsApp yang tertaut akan diputus. Anda perlu memasukkan token baru untuk menghubungkan kembali.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#e03131',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then(function (result) {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: function () { Swal.showLoading(); }
                        });
                        document.getElementById('formHapusToken').submit();
                    }
                });
                
                return false;
            };

            // ═══════ Konfirmasi Disconnect ═══════
            window.confirmDisconnect = function(event) {
                event.preventDefault();
                
                Swal.fire({
                    title: 'Putuskan Koneksi?',
                    html: 'Perangkat WhatsApp akan <strong>terputus</strong> dari sistem dan Anda perlu memindai ulang QR Code untuk menghubungkannya kembali.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, putuskan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#e03131',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then(function (result) {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memutuskan Koneksi...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: function () { Swal.showLoading(); }
                        });
                        document.getElementById('formDisconnect').submit();
                    }
                });
                
                return false;
            };

        });
    </script>
@endpush