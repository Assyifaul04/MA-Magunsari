@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-primary:       #3b5bdb;
        --brand-primary-light: #eef2ff;
        --brand-primary-dark:  #2f4ac2;
        --brand-success:       #0ca678;
        --brand-success-light: #e6fcf5;
        --brand-warning:       #f59f00;
        --brand-warning-light: #fff9db;
        --brand-danger:        #e03131;
        --brand-danger-light:  #fff5f5;
        --brand-info:          #1c7ed6;
        --brand-info-light:    #e7f5ff;
        --surface:             #ffffff;
        --surface-soft:        #f8f9fc;
        --surface-border:      #e9ecef;
        --text-primary:        #1a1d23;
        --text-secondary:      #6c757d;
        --text-muted:          #adb5bd;
        --shadow-sm:  0 1px 3px rgba(0,0,0,.06);
        --shadow-md:  0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.05);
        --shadow-lg:  0 10px 30px rgba(0,0,0,.10), 0 4px 8px rgba(0,0,0,.06);
        --radius-sm:  6px;
        --radius-md:  10px;
        --radius-lg:  14px;
        --radius-xl:  20px;
    }

    body, .section, .card { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Page Hero ────────────────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, #1c3faa 0%, var(--brand-primary) 55%, #4f75ff 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px;
        margin-bottom: 24px;
        display: flex; align-items: center;
        box-shadow: 0 8px 32px rgba(59,91,219,.28);
        position: relative; overflow: hidden;
    }
    .page-hero::before {
        content: ''; position: absolute; right: -50px; top: -50px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.07); border-radius: 50%;
    }
    .page-hero::after {
        content: ''; position: absolute; right: 80px; bottom: -60px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,.05); border-radius: 50%;
    }
    .page-hero h1 { font-size: 1.45rem; font-weight: 700; color: #fff; margin: 0 0 4px; }
    .page-hero .breadcrumb { margin: 0; background: transparent; padding: 0; font-size: .78rem; }
    .page-hero .breadcrumb-item a,
    .page-hero .breadcrumb-item.active { color: rgba(255,255,255,.75); }
    .page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    /* ── Data Card ────────────────────────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .data-card-header {
        padding: 20px 28px 16px;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
        display: flex; align-items: center; gap: 14px;
    }
    .dch-icon {
        width: 44px; height: 44px;
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        font-size: 1.15rem; flex-shrink: 0;
    }
    .dch-icon-primary { background: var(--brand-primary-light); color: var(--brand-primary); }
    .dch-icon-clock   { background: #fff3e0; color: var(--brand-warning); }
    .data-card-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .data-card-sub   { font-size: .75rem; color: var(--text-muted); margin: 0; }
    .data-card-body  { padding: 28px; }

    /* ── Alert Banner ─────────────────────────────── */
    .alert-banner {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 14px 18px; border-radius: var(--radius-md);
        font-size: .875rem; font-weight: 500;
        margin-bottom: 24px; border: none;
    }
    .alert-banner i { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
    .alert-banner-info    { background: var(--brand-info-light);    color: #1558a5; }
    .alert-banner-success { background: var(--brand-success-light); color: #087f5b; }
    .alert-banner-danger  { background: var(--brand-danger-light);  color: #c92a2a; }
    .alert-banner-warning { background: var(--brand-warning-light); color: #92600a; }
    .alert-banner .btn-close { margin-left: auto; }

    /* ── Time Field Group ─────────────────────────── */
    .time-field-group { margin-bottom: 24px; }
    .time-field-label {
        display: flex; align-items: center; gap: 8px;
        font-size: .75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: var(--text-secondary); margin-bottom: 8px;
    }
    .time-field-label .licon {
        width: 28px; height: 28px; border-radius: var(--radius-sm);
        display: grid; place-items: center; font-size: .8rem;
    }
    .licon-dawn   { background: #fff3e0; color: #e67700; }
    .licon-sun    { background: #fff9db; color: var(--brand-warning); }
    .licon-sunset { background: #f3f0ff; color: #7950f2; }

    .time-input-wrap { position: relative; }
    .time-input-wrap input[type="time"] {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.4rem; font-weight: 700;
        color: var(--text-primary);
        border: 2px solid var(--surface-border);
        border-radius: var(--radius-md);
        padding: 14px 18px;
        width: 100%;
        background: var(--surface);
        transition: border-color .2s, box-shadow .2s;
        appearance: none;
        -webkit-appearance: none;
    }
    .time-input-wrap input[type="time"]:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59,91,219,.1);
        outline: none;
    }
    .time-input-wrap input[type="time"]:disabled {
        background: var(--surface-soft);
        color: var(--text-muted);
        border-color: var(--surface-border);
        cursor: not-allowed;
    }
    .time-hint {
        font-size: .78rem; color: var(--text-muted);
        margin-top: 6px; display: flex; align-items: center; gap: 5px;
    }

    /* inline warning below time field */
    #jamMasukAlert {
        font-size: .82rem;
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        margin-top: 10px;
        border: none;
        background: var(--brand-warning-light);
        color: #92600a;
        display: flex; align-items: center; gap: 8px;
    }
    #jamMasukAlert.d-none { display: none !important; }

    /* ── Divider ──────────────────────────────────── */
    .field-divider {
        border: none;
        border-top: 1px dashed var(--surface-border);
        margin: 24px 0;
    }

    /* ── Form Actions ─────────────────────────────── */
    .form-actions { display: flex; gap: 10px; padding-top: 8px; }
    .btn-save {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .875rem; font-weight: 700;
        padding: 11px 24px; border-radius: 50px; border: none;
        background: var(--brand-primary); color: #fff;
        display: inline-flex; align-items: center; gap: 8px;
        transition: all .2s; cursor: pointer;
    }
    .btn-save:hover { background: var(--brand-primary-dark); box-shadow: 0 4px 14px rgba(59,91,219,.35); transform: translateY(-1px); }
    .btn-reset {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .875rem; font-weight: 600;
        padding: 11px 20px; border-radius: 50px;
        border: 2px solid var(--surface-border);
        background: var(--surface); color: var(--text-secondary);
        display: inline-flex; align-items: center; gap: 7px;
        transition: all .2s; cursor: pointer;
    }
    .btn-reset:hover { border-color: var(--brand-primary); color: var(--brand-primary); background: var(--brand-primary-light); }

    /* ── Right sidebar cards ──────────────────────── */
    /* Clock card */
    .clock-card {
        background: linear-gradient(135deg, #1c3faa 0%, var(--brand-primary) 55%, #4f75ff 100%);
        border-radius: var(--radius-xl);
        padding: 28px 24px;
        text-align: center;
        box-shadow: 0 8px 28px rgba(59,91,219,.25);
        position: relative; overflow: hidden; margin-bottom: 20px;
    }
    .clock-card::before {
        content: ''; position: absolute; right: -30px; top: -30px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,.07); border-radius: 50%;
    }
    .clock-label {
        font-size: .7rem; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: rgba(255,255,255,.7); margin-bottom: 8px;
    }
    #currentTime {
        font-size: 2.8rem; font-weight: 700;
        color: #fff; letter-spacing: .04em;
        line-height: 1; margin-bottom: 6px;
        font-variant-numeric: tabular-nums;
    }
    #currentDate {
        font-size: .8rem; color: rgba(255,255,255,.75);
        font-weight: 500;
    }

    /* Info accordion card */
    .info-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .info-card-header {
        padding: 18px 22px 14px;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
        display: flex; align-items: center; gap: 10px;
    }
    .info-card-title { font-size: .9rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .info-card-body  { padding: 6px 0; }

    /* accordion items */
    .acc-item {
        border-bottom: 1px solid var(--surface-border);
    }
    .acc-item:last-child { border-bottom: none; }
    .acc-toggle {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 22px;
        cursor: pointer;
        transition: background .15s;
        user-select: none;
    }
    .acc-toggle:hover { background: var(--surface-soft); }
    .acc-icon {
        width: 36px; height: 36px; border-radius: var(--radius-md);
        display: grid; place-items: center; font-size: .9rem; flex-shrink: 0;
    }
    .acc-icon-blue   { background: var(--brand-primary-light); color: var(--brand-primary); }
    .acc-icon-yellow { background: var(--brand-warning-light); color: var(--brand-warning); }
    .acc-icon-green  { background: var(--brand-success-light); color: var(--brand-success); }

    .acc-text-title { font-size: .84rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .acc-text-sub   { font-size: .72rem; color: var(--text-muted); margin: 0; }
    .acc-chevron {
        margin-left: auto; font-size: .75rem; color: var(--text-muted);
        transition: transform .22s ease;
    }
    [data-bs-toggle="collapse"].collapsed .acc-chevron,
    [aria-expanded="false"] .acc-chevron { transform: rotate(0deg); }
    [aria-expanded="true"]  .acc-chevron { transform: rotate(180deg); }

    .acc-body {
        padding: 0 22px 16px 70px;
    }
    .acc-body ul {
        margin: 0; padding: 0;
        list-style: none;
    }
    .acc-body ul li {
        font-size: .8rem; color: var(--text-secondary);
        padding: 4px 0;
        display: flex; align-items: flex-start; gap: 7px;
    }
    .acc-body ul li::before {
        content: '›';
        color: var(--brand-primary); font-weight: 700; flex-shrink: 0;
    }
    .acc-body ul li strong { color: var(--text-primary); }
</style>

<!-- ══════════════ PAGE HERO ══════════════ -->
<div class="page-hero">
    <div>
        <h1><i class="bi bi-clock-history me-2" style="opacity:.9"></i>Pengaturan Jam</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a>
                </li>
                <li class="breadcrumb-item" style="color:rgba(255,255,255,.5)">Master Data</li>
                <li class="breadcrumb-item active">Pengaturan</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="row g-4">

        <!-- ══ LEFT: Form Card ══ -->
        <div class="col-lg-8">
            <div class="data-card">
                <div class="data-card-header">
                    <div class="dch-icon dch-icon-primary"><i class="bi bi-sliders"></i></div>
                    <div>
                        <p class="data-card-title">Konfigurasi Waktu Kehadiran Siswa</p>
                        <p class="data-card-sub">Atur rentang waktu absen masuk dan pulang</p>
                    </div>
                </div>

                <div class="data-card-body">

                    @if($sudahAdaMasuk)
                    <div class="alert-banner alert-banner-info">
                        <i class="bi bi-lock-fill"></i>
                        <div>
                            <strong>Jam Masuk Terkunci —</strong>
                            Ada siswa yang sudah melakukan absen masuk hari ini. Pengaturan jam masuk tidak dapat diubah.
                        </div>
                    </div>
                    @endif

                    <form id="formPengaturan" class="needs-validation" novalidate>
                        @csrf

                        <!-- Jam Masuk Awal -->
                        <div class="time-field-group">
                            <div class="time-field-label">
                                <span class="licon licon-dawn"><i class="bi bi-sunrise"></i></span>
                                Jam Masuk Awal
                                @if($sudahAdaMasuk)
                                    <span style="background:#fee2e2;color:#c92a2a;font-size:.65rem;padding:2px 8px;border-radius:50px;margin-left:4px;">
                                        <i class="bi bi-lock-fill me-1"></i>Terkunci
                                    </span>
                                @endif
                            </div>
                            <div class="time-input-wrap">
                                <input type="time"
                                       name="jam_masuk_awal"
                                       id="jam_masuk_awal"
                                       step="60"
                                       value="{{ $pengaturan->jam_masuk_awal ?? '05:00' }}"
                                       @if($sudahAdaMasuk) disabled @endif
                                       required>
                            </div>
                            <div class="time-hint">
                                <i class="bi bi-info-circle"></i>
                                Waktu paling awal siswa dapat melakukan absen masuk
                            </div>
                        </div>

                        <hr class="field-divider">

                        <!-- Jam Masuk Akhir -->
                        <div class="time-field-group">
                            <div class="time-field-label">
                                <span class="licon licon-sun"><i class="bi bi-sun"></i></span>
                                Jam Masuk Akhir
                                @if($sudahAdaMasuk)
                                    <span style="background:#fee2e2;color:#c92a2a;font-size:.65rem;padding:2px 8px;border-radius:50px;margin-left:4px;">
                                        <i class="bi bi-lock-fill me-1"></i>Terkunci
                                    </span>
                                @endif
                            </div>
                            <div class="time-input-wrap">
                                <input type="time"
                                       name="jam_masuk_akhir"
                                       id="jam_masuk_akhir"
                                       step="60"
                                       value="{{ $pengaturan->jam_masuk_akhir ?? '07:00' }}"
                                       @if($sudahAdaMasuk) disabled @endif
                                       required>
                            </div>
                            <div class="time-hint">
                                <i class="bi bi-info-circle"></i>
                                Batas waktu terakhir absen masuk — setelah jam ini dianggap terlambat
                            </div>
                            <div id="jamMasukAlert" class="d-none">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span id="jamMasukInfo"></span>
                            </div>
                        </div>

                        <hr class="field-divider">

                        <!-- Jam Pulang -->
                        <div class="time-field-group">
                            <div class="time-field-label">
                                <span class="licon licon-sunset"><i class="bi bi-sunset"></i></span>
                                Jam Pulang
                            </div>
                            <div class="time-input-wrap">
                                <input type="time"
                                       name="jam_pulang"
                                       id="jam_pulang"
                                       step="60"
                                       value="{{ $pengaturan->jam_pulang ?? '15:00' }}"
                                       required>
                            </div>
                            <div class="time-hint">
                                <i class="bi bi-info-circle"></i>
                                Waktu siswa dapat melakukan absen pulang
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="form-actions">
                            <button type="submit" id="btnSimpan" class="btn-save">
                                <i class="bi bi-save2"></i> Simpan Pengaturan
                            </button>
                            <button type="reset" class="btn-reset">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>
                    </form>

                    <!-- Alert Messages -->
                    <div id="alertSuccess" class="alert-banner alert-banner-success mt-4 d-none" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <span class="alert-message"></span>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div id="alertError" class="alert-banner alert-banner-danger mt-4 d-none" role="alert">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                        <span class="alert-message"></span>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                </div><!-- /data-card-body -->
            </div>
        </div>

        <!-- ══ RIGHT: Sidebar ══ -->
        <div class="col-lg-4">

            <!-- Live Clock -->
            <div class="clock-card">
                <div class="clock-label"><i class="bi bi-clock me-1"></i>Waktu Saat Ini</div>
                <div id="currentTime">--:--:--</div>
                <div id="currentDate">Memuat…</div>
            </div>

            <!-- Info Accordion -->
            <div class="info-card">
                <div class="info-card-header">
                    <div class="acc-icon acc-icon-blue" style="width:32px;height:32px;font-size:.85rem;">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <p class="info-card-title">Informasi Pengaturan</p>
                </div>
                <div class="info-card-body">

                    <!-- Jam Masuk -->
                    <div class="acc-item">
                        <div class="acc-toggle" data-bs-toggle="collapse"
                             data-bs-target="#jamMasukInfo" aria-expanded="false" aria-controls="jamMasukInfo">
                            <div class="acc-icon acc-icon-blue"><i class="bi bi-clock"></i></div>
                            <div>
                                <p class="acc-text-title">Jam Masuk</p>
                                <p class="acc-text-sub">Klik untuk keterangan</p>
                            </div>
                            <i class="bi bi-chevron-down acc-chevron"></i>
                        </div>
                        <div class="collapse" id="jamMasukInfo">
                            <div class="acc-body">
                                <ul>
                                    <li><strong>Jam Masuk Awal:</strong> Waktu paling awal siswa bisa absen (contoh: 05:00)</li>
                                    <li><strong>Jam Masuk Akhir:</strong> Batas waktu absen masuk, setelah jam ini dianggap terlambat</li>
                                    <li>Siswa hanya bisa absen dalam rentang waktu ini</li>
                                    <li>Otomatis terkunci jika ada siswa yang sudah absen hari ini</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Jam Pulang -->
                    <div class="acc-item">
                        <div class="acc-toggle" data-bs-toggle="collapse"
                             data-bs-target="#jamPulangInfo" aria-expanded="false" aria-controls="jamPulangInfo">
                            <div class="acc-icon acc-icon-yellow"><i class="bi bi-calendar-check"></i></div>
                            <div>
                                <p class="acc-text-title">Jam Pulang</p>
                                <p class="acc-text-sub">Klik untuk keterangan</p>
                            </div>
                            <i class="bi bi-chevron-down acc-chevron"></i>
                        </div>
                        <div class="collapse" id="jamPulangInfo">
                            <div class="acc-body">
                                <ul>
                                    <li>Tentukan waktu siswa dapat melakukan absen pulang</li>
                                    <li>Siswa dapat absen pulang mulai dari jam yang ditentukan</li>
                                    <li>Tidak ada batas akhir untuk absen pulang</li>
                                    <li>Jam pulang bisa diubah kapan saja sesuai kebutuhan sekolah</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="acc-item">
                        <div class="acc-toggle" data-bs-toggle="collapse"
                             data-bs-target="#tipsInfo" aria-expanded="false" aria-controls="tipsInfo">
                            <div class="acc-icon acc-icon-green"><i class="bi bi-lightbulb-fill"></i></div>
                            <div>
                                <p class="acc-text-title">Tips & Trik</p>
                                <p class="acc-text-sub">Klik untuk tips</p>
                            </div>
                            <i class="bi bi-chevron-down acc-chevron"></i>
                        </div>
                        <div class="collapse" id="tipsInfo">
                            <div class="acc-body">
                                <ul>
                                    <li>Berikan toleransi waktu 15–30 menit untuk jam masuk akhir</li>
                                    <li>Sesuaikan dengan jadwal transportasi siswa</li>
                                    <li>Koordinasi dengan guru piket untuk konsistensi</li>
                                    <li>Backup pengaturan sebelum melakukan perubahan besar</li>
                                    <li>Monitor laporan keterlambatan untuk evaluasi pengaturan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div><!-- /info-card-body -->
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="{{ asset('js/pengaturan.js') }}"></script>
<script>
    // Real-time clock
    function updateClock() {
        const now = new Date();
        document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID');
        document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Chevron rotation via aria-expanded (Bootstrap handles the attribute automatically)
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (el) {
            const target = document.querySelector(el.getAttribute('data-bs-target'));
            const chevron = el.querySelector('.acc-chevron');

            target.addEventListener('show.bs.collapse', function () {
                if (chevron) { chevron.style.transform = 'rotate(180deg)'; }
            });
            target.addEventListener('hide.bs.collapse', function () {
                if (chevron) { chevron.style.transform = 'rotate(0deg)'; }
            });
        });
    });
</script>
@endpush