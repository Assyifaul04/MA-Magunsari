@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-primary:       #2f9e44;
        --brand-primary-light: #ebfbee;
        --brand-primary-dark:  #237032;
        --brand-success-light: #e6fcf5;
        --brand-success:       #0ca678;
        --brand-danger:        #e03131;
        --brand-danger-light:  #fff5f5;
        --brand-warning-light: #fff9db;
        --brand-warning:       #f59f00;
        --brand-info-light:    #e7f5ff;
        --brand-info:          #1971c2;
        --surface:             #ffffff;
        --surface-soft:        #f8fdf9;
        --surface-border:      #e9ecef;
        --text-primary:        #1a1d23;
        --text-secondary:      #6c757d;
        --text-muted:          #adb5bd;
        --shadow-md: 0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.05);
        --shadow-lg: 0 10px 30px rgba(0,0,0,.10), 0 4px 8px rgba(0,0,0,.06);
        --radius-sm: 6px;
        --radius-md: 10px;
        --radius-lg: 14px;
        --radius-xl: 20px;
    }

    body, .section, .card, .modal-content {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Page Hero ─────────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, #1a5c2a 0%, var(--brand-primary) 55%, #52c46a 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 32px rgba(47,158,68,.28);
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute; right: -50px; top: -50px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.07); border-radius: 50%;
    }
    .page-hero::after {
        content: '';
        position: absolute; right: 70px; bottom: -65px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,.05); border-radius: 50%;
    }
    .page-hero h1 {
        font-size: 1.45rem; font-weight: 700;
        color: #fff; margin: 0 0 4px;
    }
    .page-hero .breadcrumb {
        margin: 0; background: transparent; padding: 0; font-size: .78rem;
    }
    .page-hero .breadcrumb-item a,
    .page-hero .breadcrumb-item.active { color: rgba(255,255,255,.75); }
    .page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    .btn-mark-all {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600; font-size: .78rem;
        border-radius: 50px; padding: 9px 20px;
        border: 1.5px solid rgba(255,255,255,.7);
        color: #fff; background: rgba(255,255,255,.15);
        display: inline-flex; align-items: center; gap: 7px;
        cursor: pointer; transition: all .2s;
        position: relative; z-index: 1;
        backdrop-filter: blur(4px);
    }
    .btn-mark-all:hover {
        background: rgba(255,255,255,.28);
        border-color: #fff;
        box-shadow: 0 4px 14px rgba(0,0,0,.15);
        transform: translateY(-1px);
    }

    /* ── Alert ────────────────────────────── */
    .alert-pro {
        border: none; border-radius: var(--radius-md);
        padding: 14px 18px; font-size: .875rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
        margin-bottom: 16px;
    }
    .alert-pro-success { background: var(--brand-success-light); color: #087f5b; }
    .alert-pro .btn-close { margin-left: auto; }

    /* ── Data Card ────────────────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .data-card-header {
        padding: 18px 24px;
        display: flex; align-items: center; gap: 12px;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }
    .header-icon {
        width: 42px; height: 42px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: 1.1rem;
        flex-shrink: 0;
    }
    .data-card-title  { font-size: 1rem;  font-weight: 700; color: var(--text-primary); margin: 0; }
    .data-card-subtitle { font-size: .75rem; color: var(--text-muted); margin: 0; }

    /* ── Notification List ────────────────── */
    .notif-list { list-style: none; margin: 0; padding: 0; }

    .notif-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 24px;
        border-bottom: 1px solid #f1f3f7;
        transition: background .15s;
        position: relative;
    }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: #f5fdf6; }

    .notif-item.is-unread { background: var(--brand-primary-light); }
    .notif-item.is-unread:hover { background: #d3f9d8; }
    .notif-item.is-unread::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px;
        background: var(--brand-primary);
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    }

    .notif-icon-wrap {
        width: 44px; height: 44px;
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .notif-icon-wrap.unread { background: var(--brand-primary-light); color: var(--brand-primary); }
    .notif-icon-wrap.read   { background: var(--surface-soft);        color: var(--text-muted); }

    .notif-body { flex: 1; min-width: 0; }

    .notif-title {
        font-size: .875rem; font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 3px;
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    }

    .badge-baru {
        font-size: .62rem; font-weight: 700;
        background: var(--brand-danger); color: #fff;
        border-radius: 50px; padding: 2px 8px;
        letter-spacing: .04em; text-transform: uppercase;
    }

    .notif-pesan {
        font-size: .82rem; color: var(--text-secondary);
        margin: 0 0 5px; line-height: 1.55;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .notif-time {
        font-size: .73rem; color: var(--text-muted);
        display: inline-flex; align-items: center; gap: 5px;
    }

    /* ── Icon-only Action Buttons ─────────── */
    .notif-actions {
        flex-shrink: 0;
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .btn-act {
        width: 34px; height: 34px;
        border-radius: var(--radius-sm);
        border: 1.5px solid;
        background: transparent;
        display: grid; place-items: center;
        font-size: .88rem;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
        flex-shrink: 0;
    }

    .btn-act-proses {
        border-color: #bce2ff;
        color: var(--brand-info);
        background: var(--brand-info-light);
    }
    .btn-act-proses:hover {
        background: var(--brand-info);
        border-color: var(--brand-info);
        color: #fff;
        box-shadow: 0 3px 8px rgba(25,113,194,.25);
        transform: translateY(-1px);
    }

    .btn-act-hapus {
        border-color: #ffa8a8;
        color: var(--brand-danger);
        background: var(--brand-danger-light);
    }
    .btn-act-hapus:hover {
        background: var(--brand-danger);
        border-color: var(--brand-danger);
        color: #fff;
        box-shadow: 0 3px 8px rgba(224,49,49,.22);
        transform: translateY(-1px);
    }

    /* ── Empty state ──────────────────────── */
    .empty-state { text-align: center; padding: 56px 24px; }
    .empty-state-icon { font-size: 3rem; color: var(--text-muted); margin-bottom: 10px; }
    .empty-state h6 { font-weight: 700; color: var(--text-secondary); margin-bottom: 4px; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }

    /* ── Pagination ───────────────────────── */
    .pagination-wrap {
        padding: 14px 24px;
        border-top: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }
    .pagination-wrap .pagination { margin: 0; }

    /* ── SweetAlert2 custom theme ─────────── */
    .swal2-popup {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        border-radius: 16px !important;
        padding: 2rem !important;
    }
    .swal2-title {
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        color: var(--text-primary) !important;
    }
    .swal2-html-container {
        font-size: .875rem !important;
        color: var(--text-secondary) !important;
    }
    .swal2-icon.swal2-warning {
        border-color: var(--brand-warning) !important;
        color: var(--brand-warning) !important;
    }
    .swal2-confirm.swal2-styled {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-weight: 600 !important;
        font-size: .84rem !important;
        border-radius: 50px !important;
        padding: 9px 22px !important;
        background-color: var(--brand-danger) !important;
        box-shadow: none !important;
    }
    .swal2-confirm.swal2-styled:focus { box-shadow: none !important; }
    .swal2-cancel.swal2-styled {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-weight: 600 !important;
        font-size: .84rem !important;
        border-radius: 50px !important;
        padding: 9px 22px !important;
        background-color: var(--surface-border) !important;
        color: var(--text-secondary) !important;
        box-shadow: none !important;
    }
    .swal2-cancel.swal2-styled:hover { background-color: #dee2e6 !important; }
    .swal2-actions { gap: 8px !important; }
</style>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ── Page Hero ─────────────────────────────────────────────── --}}
<div class="page-hero">
    <div>
        <h1><i class="bi bi-bell me-2" style="opacity:.9"></i>Notifikasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a>
                </li>
                <li class="breadcrumb-item active">Notifikasi</li>
            </ol>
        </nav>
    </div>

    @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('notifikasi.markAllRead') }}" method="POST" style="position:relative;z-index:1">
            @csrf
            <button type="submit" class="btn-mark-all">
                <i class="bi bi-check2-all"></i> Tandai Semua Dibaca
            </button>
        </form>
    @endif
</div>

{{-- ── Content ────────────────────────────────────────────────── --}}
<section class="section">
    <div class="row">
        <div class="col-lg-12">

            @if(session('success'))
                <div class="alert-pro alert-pro-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="data-card">

                <div class="data-card-header">
                    <div class="header-icon"><i class="bi bi-bell"></i></div>
                    <div>
                        <p class="data-card-title">Semua Notifikasi</p>
                        <p class="data-card-subtitle">
                            {{ $notifikasi->total() }} notifikasi
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                &bull; <span style="color:var(--brand-danger);font-weight:700">{{ auth()->user()->unreadNotifications->count() }} belum dibaca</span>
                            @endif
                        </p>
                    </div>
                </div>

                <ul class="notif-list">
                    @forelse($notifikasi as $notif)
                        <li class="notif-item {{ $notif->unread() ? 'is-unread' : '' }}">

                            {{-- Icon --}}
                            <div class="notif-icon-wrap {{ $notif->unread() ? 'unread' : 'read' }}">
                                <i class="bi {{ $notif->data['icon'] ?? 'bi-info-circle' }}"></i>
                            </div>

                            {{-- Body --}}
                            <div class="notif-body">
                                <p class="notif-title">
                                    {{ $notif->data['nama_siswa'] ?? 'Pemberitahuan' }}
                                    @if($notif->unread())
                                        <span class="badge-baru">Baru</span>
                                    @endif
                                </p>
                                <p class="notif-pesan">{{ $notif->data['pesan'] ?? '' }}</p>
                                <span class="notif-time">
                                    <i class="bi bi-clock"></i>
                                    {{ $notif->created_at->diffForHumans() }}
                                    &bull;
                                    {{ $notif->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>

                            {{-- Actions – icon only --}}
                            <div class="notif-actions">
                                @php
                                    $siswaId     = $notif->data['siswa_id'] ?? null;
                                    $statusSiswa = $siswaId
                                        ? \App\Models\Siswa::where('id', $siswaId)->value('status')
                                        : null;
                                @endphp

                                @if($statusSiswa != 'pending')
                                    {{-- Hapus dengan SweetAlert --}}
                                    <form class="form-hapus-notif"
                                          action="{{ route('notifikasi.destroy', $notif->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn-act btn-act-hapus btn-hapus-notif"
                                                title="Hapus Notifikasi">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('rfid.laporan-hilang') }}"
                                       class="btn-act btn-act-proses"
                                       title="Proses Sekarang">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </a>
                                @endif
                            </div>

                        </li>
                    @empty
                        <li>
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-bell-slash"></i></div>
                                <h6>Belum ada notifikasi</h6>
                                <small>Semua notifikasi akan muncul di sini</small>
                            </div>
                        </li>
                    @endforelse
                </ul>

                @if($notifikasi->hasPages())
                    <div class="pagination-wrap">
                        {{ $notifikasi->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // ── Hapus Notifikasi via SweetAlert2 ──────────────────────
    document.querySelectorAll('.btn-hapus-notif').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const form = this.closest('.form-hapus-notif');
            Swal.fire({
                title: 'Hapus Notifikasi?',
                text: 'Notifikasi ini akan dihapus secara permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
                cancelButtonText: '<i class="bi bi-x me-1"></i> Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup:      'swal2-popup',
                    title:      'swal2-title',
                    confirmButton: 'swal2-confirm swal2-styled',
                    cancelButton:  'swal2-cancel swal2-styled',
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if(session('success'))
        Swal.fire({
            toast: false,
            position: 'center',
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: true,
            confirmButtonText: 'OK',
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup:         'swal2-popup',
                title:         'swal2-title',
                confirmButton: 'swal2-confirm swal2-styled',
            }
        });
    @endif
</script>
@endpush