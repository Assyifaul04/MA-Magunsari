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
        --wa-green:            #25d366;
        --wa-green-dark:       #1da851;
        --wa-green-light:      #e8fdf0;
        --surface:             #ffffff;
        --surface-soft:        #f8f9fc;
        --surface-border:      #e9ecef;
        --text-primary:        #1a1d23;
        --text-secondary:      #6c757d;
        --text-muted:          #adb5bd;
        --shadow-sm:  0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md:  0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.05);
        --shadow-lg:  0 10px 30px rgba(0,0,0,.10), 0 4px 8px rgba(0,0,0,.06);
        --radius-sm:  6px;
        --radius-md:  10px;
        --radius-lg:  14px;
        --radius-xl:  20px;
    }

    body, .section, .card, .modal-content {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Page Hero ─────────────────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, #075e54 0%, var(--wa-green-dark) 55%, var(--wa-green) 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 32px rgba(29,168,81,.3);
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute; right: -50px; top: -50px;
        width: 210px; height: 210px;
        background: rgba(255,255,255,.07); border-radius: 50%;
    }
    .page-hero::after {
        content: '';
        position: absolute; right: 80px; bottom: -70px;
        width: 150px; height: 150px;
        background: rgba(255,255,255,.05); border-radius: 50%;
    }
    .page-hero-left h1 {
        font-size: 1.45rem; font-weight: 700;
        color: #fff; margin: 0 0 4px;
    }
    .page-hero-left .breadcrumb {
        margin: 0; background: transparent; padding: 0; font-size: .78rem;
    }
    .page-hero-left .breadcrumb-item a,
    .page-hero-left .breadcrumb-item.active { color: rgba(255,255,255,.75); }
    .page-hero-left .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    /* ── Alert ────────────────────────────────────── */
    .alert-pro {
        border: none; border-radius: var(--radius-md);
        padding: 14px 18px; font-size: .875rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        box-shadow: var(--shadow-sm); margin-bottom: 16px;
    }
    .alert-pro-success { background: var(--brand-success-light); color: #087f5b; }
    .alert-pro .btn-close { margin-left: auto; }

    /* ── Data Card ────────────────────────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .data-card-header {
        padding: 18px 24px 14px;
        display: flex; align-items: center; justify-content: space-between;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }
    .data-card-header-left { display: flex; align-items: center; gap: 12px; }
    .header-icon {
        width: 42px; height: 42px;
        background: var(--wa-green-light);
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        color: var(--wa-green-dark); font-size: 1.1rem;
    }
    .data-card-title    { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .data-card-subtitle { font-size: .75rem; color: var(--text-muted); margin: 0; }

    /* ── Table ────────────────────────────────────── */
    .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-pro thead th {
        background: var(--surface-soft);
        color: var(--text-secondary);
        font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        padding: 13px 18px;
        border-bottom: 1px solid var(--surface-border);
        white-space: nowrap;
    }
    .table-pro tbody tr { transition: background .15s; }
    .table-pro tbody tr:hover { background: #f5fff9; }
    .table-pro tbody td {
        padding: 13px 18px;
        border-bottom: 1px solid #f1f3f7;
        vertical-align: middle;
        font-size: .855rem;
        color: var(--text-primary);
    }
    .table-pro tbody tr:last-child td { border-bottom: none; }

    /* time chip */
    .time-chip {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: .78rem; font-weight: 600;
        color: var(--text-secondary);
        background: var(--surface-soft);
        border: 1px solid var(--surface-border);
        border-radius: 50px;
        padding: 4px 10px;
        white-space: nowrap;
    }
    .time-chip i { font-size: .72rem; color: var(--text-muted); }

    /* student info */
    .student-name  { font-weight: 700; color: var(--text-primary); font-size: .88rem; }
    .student-class { font-size: .75rem; color: var(--text-muted); margin-top: 2px; }

    /* nomor WA */
    .wa-number {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: .76rem; font-weight: 600;
        background: var(--wa-green-light);
        color: var(--wa-green-dark);
        border: 1px solid rgba(37,211,102,.2);
        padding: 3px 9px; border-radius: 50px;
        margin-top: 4px;
    }
    .ortu-name { font-weight: 600; color: var(--text-secondary); font-size: .85rem; }

    /* pesan preview */
    .pesan-preview {
        max-width: 240px;
        font-size: .82rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    /* status badges */
    .badge-status {
        font-size: .72rem; font-weight: 700;
        padding: 4px 11px; border-radius: 50px;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .badge-terkirim { background: var(--wa-green-light);    color: var(--wa-green-dark); }
    .badge-gagal    { background: var(--brand-danger-light); color: var(--brand-danger); }
    .badge-pending  { background: var(--brand-warning-light); color: #b45309; }

    /* action buttons */
    .action-wrap { display: flex; gap: 6px; align-items: center; justify-content: center; }
    .btn-act {
        width: 32px; height: 32px;
        border-radius: var(--radius-sm);
        border: 1.5px solid; background: transparent;
        display: grid; place-items: center;
        font-size: .82rem; cursor: pointer;
        transition: all .2s;
    }
    .btn-act-detail {
        border-color: #a5d8ff; color: var(--brand-info);
        background: var(--brand-info-light);
    }
    .btn-act-detail:hover { background: var(--brand-info); border-color: var(--brand-info); color: #fff; }
    .btn-act-delete {
        border-color: #ffa8a8; color: var(--brand-danger);
        background: var(--brand-danger-light);
    }
    .btn-act-delete:hover { background: var(--brand-danger); border-color: var(--brand-danger); color: #fff; }

    /* empty state */
    .empty-state { text-align: center; padding: 56px 24px; }
    .empty-state-icon { font-size: 3.2rem; color: var(--text-muted); margin-bottom: 12px; }
    .empty-state h6 { font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }

    /* pagination area */
    .pagination-wrap {
        padding: 14px 24px;
        border-top: 1px solid var(--surface-border);
        background: var(--surface-soft);
        display: flex; justify-content: flex-end;
    }

    /* ── Modal ────────────────────────────────────── */
    .modal-pro .modal-content {
        border: none; border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg); overflow: hidden;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .modal-pro .modal-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }
    .modal-pro .modal-title {
        font-size: .95rem; font-weight: 700;
        color: var(--text-primary);
        display: flex; align-items: center; gap: 10px;
    }
    .mti {
        width: 34px; height: 34px; border-radius: var(--radius-sm);
        display: grid; place-items: center; font-size: .9rem; flex-shrink: 0;
    }
    .mti-info    { background: var(--brand-info-light);  color: var(--brand-info); }
    .mti-success { background: var(--wa-green-light);    color: var(--wa-green-dark); }

    .modal-pro .modal-body   { padding: 22px 24px; }
    .modal-pro .modal-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }

    /* detail info card */
    .detail-card {
        background: var(--surface);
        border: 1.5px solid var(--surface-border);
        border-radius: var(--radius-lg);
        padding: 18px 20px;
        height: 100%;
    }
    .detail-label {
        font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: var(--text-muted); margin-bottom: 4px;
    }
    .detail-value {
        font-size: .88rem; font-weight: 600;
        color: var(--text-primary); margin-bottom: 14px;
    }
    .detail-value:last-child { margin-bottom: 0; }

    /* WA bubble in modal */
    .wa-bubble-wrap {
        background: #ece5dd;
        border-radius: var(--radius-lg);
        padding: 12px 14px;
        display: flex; justify-content: flex-end;
    }
    .wa-bubble {
        background: #dcf8c6;
        border-radius: 10px 10px 0 10px;
        padding: 10px 14px;
        font-size: .88rem;
        color: #1a1d23;
        line-height: 1.6;
        white-space: pre-wrap;
        max-width: 95%;
        box-shadow: 0 1px 2px rgba(0,0,0,.1);
        font-family: 'Segoe UI', sans-serif;
    }

    /* terminal block */
    .terminal-block {
        background: #1e2533;
        border-radius: var(--radius-md);
        padding: 14px 16px;
    }
    .terminal-block .terminal-title {
        font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em;
        color: #6c7a8d; margin-bottom: 10px;
        display: flex; align-items: center; gap: 6px;
    }
    .terminal-block pre {
        margin: 0;
        font-size: .82rem;
        color: #63e6be;
        max-height: 220px;
        overflow-y: auto;
        white-space: pre-wrap;
        word-break: break-all;
    }

    /* modal loading */
    .modal-loading { text-align: center; padding: 48px 24px; }
    .modal-loading .spinner-border { width: 2.8rem; height: 2.8rem; color: var(--wa-green); }
    .modal-loading p { margin-top: 12px; font-weight: 600; color: var(--text-secondary); font-size: .88rem; }

    /* modal close btn */
    .btn-modal {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .84rem; font-weight: 600;
        padding: 9px 20px; border-radius: 50px; border: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s; cursor: pointer;
    }
    .btn-mc { background: var(--surface-border); color: var(--text-secondary); }
    .btn-mc:hover { background: #dee2e6; color: var(--text-primary); }
</style>

<!-- ══════════════ PAGE HERO ══════════════ -->
<div class="page-hero">
    <div class="page-hero-left">
        <h1><i class="bi bi-whatsapp me-2" style="opacity:.9"></i>Log Notifikasi WhatsApp</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active">Log Notifikasi WA</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            @if(session('success'))
                <div class="alert-pro alert-pro-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="data-card">

                <!-- Header -->
                <div class="data-card-header">
                    <div class="data-card-header-left">
                        <div class="header-icon"><i class="bi bi-chat-square-dots"></i></div>
                        <div>
                            <p class="data-card-title">Riwayat Pengiriman Pesan</p>
                            <p class="data-card-subtitle">Log seluruh notifikasi WhatsApp yang dikirim sistem</p>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table-pro">
                        <thead>
                            <tr>
                                <th style="width:12%">Waktu</th>
                                <th style="width:16%">Siswa</th>
                                <th style="width:18%">Target (No. WA)</th>
                                <th style="width:30%">Pesan</th>
                                <th style="width:10%;text-align:center">Status</th>
                                <th style="width:10%;text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifikasis as $notif)
                            <tr>
                                <td>
                                    <span class="time-chip">
                                        <i class="bi bi-clock"></i>
                                        {{ \Carbon\Carbon::parse($notif->created_at)->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="student-name">{{ $notif->siswa->nama ?? 'Siswa Terhapus' }}</div>
                                    <div class="student-class">{{ $notif->siswa->kelas->nama ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="ortu-name">{{ $notif->orangTua->nama ?? '-' }}</div>
                                    <div class="wa-number">
                                        <i class="bi bi-whatsapp"></i>
                                        {{ $notif->nomor_whatsapp }}
                                    </div>
                                </td>
                                <td>
                                    <span class="pesan-preview" title="{{ $notif->pesan }}">
                                        {{ $notif->pesan }}
                                    </span>
                                </td>
                                <td style="text-align:center">
                                    @if($notif->status === 'terkirim')
                                        <span class="badge-status badge-terkirim"><i class="bi bi-check-all"></i>Terkirim</span>
                                    @elseif($notif->status === 'gagal')
                                        <span class="badge-status badge-gagal"><i class="bi bi-x-circle-fill"></i>Gagal</span>
                                    @else
                                        <span class="badge-status badge-pending"><i class="bi bi-clock-fill"></i>Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-wrap">
                                        <button type="button"
                                                class="btn-act btn-act-detail btn-detail"
                                                data-url="{{ route('notifikasiwa.show', $notif->id) }}"
                                                title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <form action="{{ route('notifikasiwa.destroy', $notif->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus log notifikasi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-act btn-act-delete" title="Hapus Log">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-chat-square-text"></i></div>
                                        <h6>Belum ada log notifikasi WhatsApp</h6>
                                        <small>Riwayat pesan yang dikirim akan otomatis muncul di sini.</small>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrap">
                    {{ $notifikasis->links() }}
                </div>

            </div>
        </div>
    </div>
</section>


<!-- ══════════════ MODAL: DETAIL NOTIFIKASI ══════════════ -->
<div class="modal fade modal-pro" id="detailNotifModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="mti mti-info"><i class="bi bi-whatsapp"></i></span>
                    Detail Notifikasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Loading -->
                <div id="modalLoading" class="modal-loading">
                    <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>
                    <p>Mengambil data notifikasi...</p>
                </div>

                <!-- Content -->
                <div id="modalContent" class="d-none">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="detail-card">
                                <div class="detail-label">Status Pengiriman</div>
                                <div id="dt-status" class="detail-value"></div>

                                <div class="detail-label">Waktu Dibuat</div>
                                <div id="dt-waktu" class="detail-value"></div>

                                <div class="detail-label">Waktu Terkirim</div>
                                <div id="dt-waktu-kirim" class="detail-value"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-card">
                                <div class="detail-label">Siswa / Kelas</div>
                                <div id="dt-siswa" class="detail-value"></div>

                                <div class="detail-label">Orang Tua</div>
                                <div id="dt-ortu" class="detail-value"></div>

                                <div class="detail-label">Nomor WhatsApp Target</div>
                                <div id="dt-nomor" class="detail-value" style="color:var(--wa-green-dark)"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-label mb-2"><i class="bi bi-chat-left-text me-1"></i>Pesan Dikirim</div>
                            <div class="wa-bubble-wrap">
                                <div class="wa-bubble" id="dt-pesan"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="terminal-block">
                                <div class="terminal-title">
                                    <i class="bi bi-terminal"></i> Response Gateway (Fonnte)
                                </div>
                                <pre id="dt-response"></pre>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-mc" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Bootstrap Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

        // Detail button click
        $('.btn-detail').on('click', function () {
            let url   = $(this).data('url');
            let modal = $('#detailNotifModal');

            modal.modal('show');
            $('#modalLoading').removeClass('d-none');
            $('#modalContent').addClass('d-none');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    if (response.success) {
                        let data = response.data;

                        $('#dt-waktu').text(new Date(data.created_at).toLocaleString('id-ID'));
                        $('#dt-waktu-kirim').text(data.dikirim_pada ? new Date(data.dikirim_pada).toLocaleString('id-ID') : '-');
                        $('#dt-pesan').text(data.pesan);
                        $('#dt-nomor').text(data.nomor_whatsapp);

                        let namaSiswa = data.siswa ? data.siswa.nama : 'Siswa Terhapus';
                        let namaKelas = (data.siswa && data.siswa.kelas) ? data.siswa.kelas.nama : '-';
                        $('#dt-siswa').text(namaSiswa + ' (Kelas: ' + namaKelas + ')');
                        $('#dt-ortu').text(data.orang_tua ? data.orang_tua.nama : '-');

                        let statusHtml = '';
                        if (data.status === 'terkirim') {
                            statusHtml = '<span class="badge-status badge-terkirim"><i class="bi bi-check-all"></i>Terkirim</span>';
                        } else if (data.status === 'gagal') {
                            statusHtml = '<span class="badge-status badge-gagal"><i class="bi bi-x-circle-fill"></i>Gagal</span>';
                        } else {
                            statusHtml = '<span class="badge-status badge-pending"><i class="bi bi-clock-fill"></i>Pending</span>';
                        }
                        $('#dt-status').html(statusHtml);

                        let responseText = data.response_gateway;
                        try {
                            let parsed = typeof data.response_gateway === 'string'
                                ? JSON.parse(data.response_gateway)
                                : data.response_gateway;
                            responseText = JSON.stringify(parsed, null, 4);
                        } catch (e) {}
                        $('#dt-response').text(responseText || 'Tidak ada response dari server gateway.');

                        $('#modalLoading').addClass('d-none');
                        $('#modalContent').removeClass('d-none');
                    }
                },
                error: function () {
                    $('#modalLoading').addClass('d-none');
                    alert('Gagal mengambil detail data! Pastikan koneksi internet Anda stabil.');
                    modal.modal('hide');
                }
            });
        });
    });
</script>
@endpush