@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $dashRoute = match($user->role) {
        'superAdmin' => route('superAdmin.dashboard'),
        'admin'      => route('admin.dashboard'),
        'guru'       => route('guru.dashboard'),
        default      => '#',
    };
    $roleLabel = match($user->role) {
        'superAdmin' => 'Super Administrator',
        'admin'      => 'Administrator',
        'guru'       => 'Guru',
        default      => $user->role,
    };
    $roleBadgeClass = match($user->role) {
        'superAdmin' => 'role-super',
        'admin'      => 'role-admin',
        'guru'       => 'role-guru',
        default      => 'role-admin',
    };
    $roleIcon = match($user->role) {
        'superAdmin' => 'bi-shield-fill-check',
        'admin'      => 'bi-person-gear',
        'guru'       => 'bi-mortarboard-fill',
        default      => 'bi-person-fill',
    };
@endphp

<style>
    /* ── Base ─────────────────────────────── */
    #header { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Header Shell ─────────────────────── */
    #header.header {
        background: #ffffff;
        border-bottom: 1px solid #e3f0e6;
        box-shadow: 0 1px 0 #e3f0e6, 0 4px 20px rgba(47,158,68,.06);
        height: 64px;
        padding: 0 16px;
        z-index: 997;
    }
    @media (min-width: 768px) {
        #header.header { padding: 0 24px; }
    }

    .header-inner {
        display: flex;
        align-items: center;
        width: 100%;
        height: 100%;
        gap: 4px;
    }

    /* ── Logo ─────────────────────────────── */
    .header-logo {
        display: flex; align-items: center; gap: 8px;
        text-decoration: none; flex-shrink: 0;
    }
    .header-logo-img {
        height: 34px; width: 34px;
        border-radius: 10px; object-fit: cover;
        box-shadow: 0 2px 8px rgba(47,158,68,.22);
        flex-shrink: 0;
    }
    @media (min-width: 576px) {
        .header-logo-img { height: 36px; width: 36px; }
    }
    .header-logo-text {
        display: none;
        flex-direction: column;
        line-height: 1.2;
    }
    /* Show logo text from sm up */
    @media (min-width: 480px) { .header-logo-text { display: flex; } }

    .header-logo-name  { font-size: .85rem; font-weight: 700; color: #1a1d2e; white-space: nowrap; letter-spacing: -.01em; }
    .header-logo-sub   { font-size: .62rem; font-weight: 600; color: #2f9e44; text-transform: uppercase; letter-spacing: .08em; margin-top: 1px; white-space: nowrap; }

    @media (min-width: 992px) {
        .header-logo-name { font-size: .875rem; }
        .header-logo-sub  { font-size: .65rem; }
    }

    /* ── Divider ──────────────────────────── */
    .header-divider {
        width: 1px; height: 28px;
        background: #e3f0e6;
        margin: 0 8px;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .header-divider { margin: 0 16px; }
    }

    /* ── Sidebar Toggle ───────────────────── */
    .toggle-sidebar-btn {
        font-size: 1.2rem; color: #7c85a2; cursor: pointer;
        padding: 7px 9px; border-radius: 9px; border: 1px solid transparent;
        transition: all .18s ease; line-height: 1; display: grid; place-items: center;
        flex-shrink: 0;
    }
    .toggle-sidebar-btn:hover { background: #ebfbee; border-color: #b2f0cb; color: #2f9e44; }

    /* ── Nav Right ────────────────────────── */
    .header-nav-right {
        display: flex; align-items: center; gap: 4px;
        margin-left: auto;
        flex-shrink: 0;
    }
    @media (min-width: 576px) {
        .header-nav-right { gap: 6px; }
    }

    /* ════════════════════════════════════════
       NOTIFIKASI
    ════════════════════════════════════════ */

    .notif-trigger {
        position: relative;
        display: flex; align-items: center; justify-content: center;
        width: 38px; height: 38px; border-radius: 50%;
        color: #7c85a2; font-size: 1.1rem;
        background: #f6fdf8; border: 1.5px solid #e3f0e6;
        transition: all .2s ease; text-decoration: none;
        flex-shrink: 0;
    }
    @media (min-width: 576px) {
        .notif-trigger { width: 40px; height: 40px; font-size: 1.2rem; }
    }
    .notif-trigger:hover, .notif-trigger.show {
        background: #ebfbee; color: #2f9e44;
        border-color: #95d5a8;
        box-shadow: 0 0 0 4px rgba(47,158,68,.10);
    }
    .notif-bell-icon { position: relative; display: grid; place-items: center; }
    @keyframes bell-wiggle {
        0%, 100% { transform: rotate(0deg); }
        15%  { transform: rotate(15deg); }
        30%  { transform: rotate(-12deg); }
        45%  { transform: rotate(10deg); }
        60%  { transform: rotate(-8deg); }
        75%  { transform: rotate(5deg); }
    }
    .notif-trigger.has-unread .notif-bell-icon { animation: bell-wiggle 2.4s ease infinite; }

    .notif-badge {
        position: absolute; top: -5px; right: -5px;
        background: linear-gradient(135deg, #e03131, #ff6b6b);
        color: #fff; font-size: .6rem; font-weight: 800;
        min-width: 18px; height: 18px;
        padding: 0 5px; border-radius: 50px;
        border: 2px solid #fff;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 6px rgba(224,49,49,.35);
        line-height: 1;
    }

    /* Dropdown shell — responsive width */
    .notif-dropdown {
        /* Mobile: nearly full viewport width */
        width: calc(100vw - 24px);
        max-width: 360px;
        padding: 0;
        border: 1px solid #e3f0e6;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(47,158,68,.12), 0 2px 8px rgba(0,0,0,.06);
        margin-top: 12px !important;
        overflow: hidden;
    }
    @media (min-width: 480px) {
        .notif-dropdown { width: 360px; }
    }

    /* Dropdown header */
    .notif-head {
        padding: 12px 16px;
        background: linear-gradient(135deg, #1a6b2a 0%, #2f9e44 60%, #52c46a 100%);
        display: flex; justify-content: space-between; align-items: center;
        position: relative; overflow: hidden;
    }
    @media (min-width: 576px) {
        .notif-head { padding: 14px 18px; }
    }
    .notif-head::before {
        content: ''; position: absolute; right: -30px; top: -30px;
        width: 100px; height: 100px;
        background: rgba(255,255,255,.07); border-radius: 50%;
    }
    .notif-head-left { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }
    .notif-head-icon {
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.2);
        display: grid; place-items: center; color: #fff; font-size: .9rem;
        flex-shrink: 0;
    }
    .notif-head-title { font-size: .85rem; font-weight: 800; color: #fff; margin: 0; }
    .notif-head-sub   { font-size: .65rem; color: rgba(255,255,255,.75); margin: 0; font-weight: 500; }
    .notif-count-pill {
        background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.25);
        color: #fff; font-size: .68rem; font-weight: 800;
        padding: 3px 10px; border-radius: 50px;
        display: inline-flex; align-items: center; gap: 4px;
        position: relative; z-index: 1;
        backdrop-filter: blur(4px);
        white-space: nowrap;
        flex-shrink: 0;
    }
    .notif-count-dot { width: 6px; height: 6px; background: #ff6b6b; border-radius: 50%; display: inline-block; }

    /* Notif list body */
    .notif-body { max-height: 280px; overflow-y: auto; }
    @media (min-width: 576px) {
        .notif-body { max-height: 310px; }
    }
    .notif-body::-webkit-scrollbar { width: 3px; }
    .notif-body::-webkit-scrollbar-thumb { background: rgba(47,158,68,.2); border-radius: 3px; }

    /* Individual notif item */
    .notif-item {
        display: flex; gap: 10px;
        padding: 12px 14px;
        border-bottom: 1px solid #f1f7f3;
        text-decoration: none;
        transition: background .18s;
        align-items: flex-start;
        position: relative;
    }
    @media (min-width: 576px) {
        .notif-item { gap: 12px; padding: 14px 16px; }
    }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: #f6fdf8; }
    .notif-item.unread::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 3px; background: linear-gradient(to bottom, #2f9e44, #52c46a);
        border-radius: 0 3px 3px 0;
    }

    .notif-icon {
        width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
        display: grid; place-items: center; font-size: .95rem;
        box-shadow: 0 2px 6px rgba(0,0,0,.08);
    }
    @media (min-width: 576px) {
        .notif-icon { width: 38px; height: 38px; font-size: 1rem; }
    }
    .notif-icon-warn    { background: #fff9db; color: #f59f00; }
    .notif-icon-danger  { background: #fff5f5; color: #e03131; }
    .notif-icon-success { background: #ebfbee; color: #2f9e44; }
    .notif-icon-info    { background: #e7f5ff; color: #1c7ed6; }

    .notif-content { flex: 1; min-width: 0; }
    .notif-title {
        font-size: .78rem; font-weight: 700; color: #1a1d2e;
        margin-bottom: 2px; line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
    }
    .notif-title b { color: #2f9e44; }
    .notif-title .notif-kelas {
        display: inline-flex; align-items: center;
        background: #ebfbee; color: #237032;
        padding: 1px 7px; border-radius: 4px;
        font-size: .65rem; font-weight: 800;
        letter-spacing: .03em; margin-left: 3px;
    }
    .notif-msg  { font-size: .74rem; color: #6c757d; line-height: 1.45; margin: 0; }
    .notif-meta {
        display: flex; align-items: center; gap: 6px;
        margin-top: 5px;
    }
    .notif-time {
        font-size: .65rem; color: #8fa89b; font-weight: 600;
        display: inline-flex; align-items: center; gap: 3px;
    }
    .notif-unread-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: #2f9e44; flex-shrink: 0;
    }

    .notif-footer {
        padding: 11px 16px;
        border-top: 1px solid #e3f0e6;
        background: #f6fdf8;
        display: flex; justify-content: center;
    }
    .notif-footer-link {
        font-size: .75rem; font-weight: 700; color: #2f9e44;
        text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
        transition: color .15s;
    }
    .notif-footer-link:hover { color: #237032; }

    .notif-empty {
        padding: 30px 20px; text-align: center;
    }
    @media (min-width: 576px) {
        .notif-empty { padding: 36px 20px; }
    }
    .notif-empty-icon {
        width: 52px; height: 52px; border-radius: 50%;
        background: #f6fdf8; border: 1.5px solid #e3f0e6;
        margin: 0 auto 12px;
        display: grid; place-items: center;
        font-size: 1.4rem; color: #b2d8be;
    }
    .notif-empty-title { font-size: .82rem; font-weight: 700; color: #495057; margin: 0 0 4px; }
    .notif-empty-sub   { font-size: .72rem; color: #8fa89b; margin: 0; }

    /* ════════════════════════════════════════
       PROFILE
    ════════════════════════════════════════ */

    .profile-trigger {
        display: flex; align-items: center; gap: 8px;
        /* Mobile: avatar-only pill */
        padding: 4px;
        border-radius: 50px;
        border: 1.5px solid #e3f0e6; background: #f6fdf8;
        cursor: pointer; text-decoration: none;
        transition: all .2s ease;
        flex-shrink: 0;
    }
    @media (min-width: 576px) {
        .profile-trigger { padding: 5px 10px 5px 5px; gap: 10px; }
    }
    @media (min-width: 768px) {
        .profile-trigger { padding: 5px 12px 5px 6px; }
    }
    .profile-trigger:hover, .profile-trigger.show {
        border-color: #95d5a8;
        box-shadow: 0 0 0 4px rgba(47,158,68,.10);
        background: #fff;
    }
    .profile-avatar {
        width: 30px; height: 30px; border-radius: 50%; object-fit: cover;
        flex-shrink: 0; border: 2px solid #fff;
        box-shadow: 0 1px 5px rgba(0,0,0,.12);
    }
    @media (min-width: 576px) {
        .profile-avatar { width: 32px; height: 32px; }
    }

    /* Profile info: hidden on mobile, show from sm */
    .profile-info { display: none; }
    @media (min-width: 576px) { .profile-info { display: block; } }

    .profile-username {
        font-size: .8rem; font-weight: 700; color: #1a1d2e;
        white-space: nowrap; max-width: 100px;
        overflow: hidden; text-overflow: ellipsis; letter-spacing: -.01em;
    }
    @media (min-width: 768px) {
        .profile-username { max-width: 140px; }
    }
    .profile-role-pill {
        font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
        padding: 2px 8px; border-radius: 50px;
        display: inline-flex; align-items: center; gap: 3px; margin-top: 2px;
    }
    .role-super { background: #fff0f6; color: #c2255c; }
    .role-admin { background: #ebfbee; color: #2f9e44; }
    .role-guru  { background: #e6fcf5; color: #0ca678; }

    /* Caret: hidden on mobile to save space */
    .profile-caret {
        font-size: .65rem; color: #bcc3d8;
        transition: transform .22s ease; flex-shrink: 0;
        display: none;
    }
    @media (min-width: 576px) {
        .profile-caret { display: block; }
    }
    .profile-trigger.show .profile-caret { transform: rotate(180deg); color: #2f9e44; }

    /* Profile Dropdown — responsive width */
    .profile-dropdown {
        min-width: 240px;
        width: calc(100vw - 24px);
        max-width: 256px;
        border: 1px solid #e3f0e6; border-radius: 18px;
        box-shadow: 0 8px 32px rgba(47,158,68,.12), 0 2px 6px rgba(0,0,0,.05);
        overflow: hidden; padding: 0; margin-top: 12px !important;
    }
    @media (min-width: 480px) {
        .profile-dropdown { width: 256px; }
    }

    .dd-profile-head {
        padding: 18px 16px 14px; text-align: center;
        background: linear-gradient(135deg, #1a6b2a 0%, #2f9e44 55%, #52c46a 100%);
        position: relative; overflow: hidden;
    }
    @media (min-width: 576px) {
        .dd-profile-head { padding: 20px 16px 16px; }
    }
    .dd-profile-head::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 130px; height: 130px; background: rgba(255,255,255,.07); border-radius: 50%;
    }
    .dd-profile-head::after {
        content: ''; position: absolute; left: -20px; bottom: -30px;
        width: 90px; height: 90px; background: rgba(255,255,255,.05); border-radius: 50%;
    }
    .dd-avatar-wrap { position: relative; display: inline-block; margin-bottom: 10px; }
    .dd-avatar {
        width: 54px; height: 54px; border-radius: 50%; object-fit: cover;
        border: 3px solid rgba(255,255,255,.4);
        box-shadow: 0 4px 14px rgba(0,0,0,.2); position: relative;
    }
    @media (min-width: 576px) {
        .dd-avatar { width: 58px; height: 58px; }
    }
    .dd-status-dot {
        position: absolute; bottom: 2px; right: 2px;
        width: 13px; height: 13px; background: #51cf66;
        border-radius: 50%; border: 2.5px solid rgba(255,255,255,.9);
        box-shadow: 0 1px 4px rgba(0,0,0,.2);
    }
    .dd-name { font-size: .85rem; font-weight: 700; color: #fff; margin: 0 0 6px; position: relative; letter-spacing: -.01em; }
    @media (min-width: 576px) {
        .dd-name { font-size: .88rem; }
    }
    .dd-role-badge {
        font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em;
        padding: 4px 12px; border-radius: 50px;
        background: rgba(255,255,255,.18); color: rgba(255,255,255,.95);
        display: inline-flex; align-items: center; gap: 5px;
        position: relative; backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,.15);
    }
    .dd-body { padding: 8px 0 6px; }
    .dd-item {
        display: flex; align-items: center; gap: 11px;
        padding: 9px 14px; font-size: .82rem; font-weight: 600;
        color: #1a1d2e; text-decoration: none;
        transition: background .15s ease;
        cursor: pointer; border: none; background: none;
        width: 100%; text-align: left; letter-spacing: -.01em;
    }
    @media (min-width: 576px) {
        .dd-item { padding: 9px 16px; }
    }
    .dd-item:hover { background: #f0faf2; color: #1a1d2e; }
    .dd-item-icon {
        width: 32px; height: 32px; border-radius: 9px;
        display: grid; place-items: center; font-size: .82rem; flex-shrink: 0;
    }
    .dd-icon-blue  { background: #ebfbee; color: #2f9e44; }
    .dd-icon-gray  { background: #f3f4f8; color: #6c757d; }
    .dd-icon-green { background: #e6fcf5; color: #0ca678; }
    .dd-icon-red   { background: #fff5f5; color: #e03131; }
    .dd-divider { border-color: #f1f7f3; margin: 4px 0; }
    .dd-item-logout { color: #e03131; }
    .dd-item-logout:hover { background: #fff5f5; color: #c92a2a; }
</style>

<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="header-inner">

        <a href="{{ $dashRoute }}" class="header-logo">
            <img src="{{ asset('image/Logo.png') }}" alt="Logo MAS Nurul Huda" class="header-logo-img">
            <div class="header-logo-text">
                <span class="header-logo-name">MAS Nurul Huda</span>
                <span class="header-logo-sub">Sistem Absensi Digital</span>
            </div>
        </a>

        <div class="header-divider"></div>

        <i class="bi bi-list toggle-sidebar-btn"></i>

        <div class="header-nav-right">

            {{-- ── Notifikasi (admin & superAdmin) ── --}}
            @if(in_array($user->role, ['admin', 'superAdmin']))
                @php $unreadNotifs = $user->unreadNotifications; @endphp

                <div class="nav-item dropdown">
                    <a class="notif-trigger {{ $unreadNotifs->count() > 0 ? 'has-unread' : '' }}"
                       href="#"
                       data-bs-toggle="dropdown"
                       data-bs-display="static"
                       aria-expanded="false"
                       id="notifDropdown">
                        <span class="notif-bell-icon">
                            <i class="bi bi-bell-fill"></i>
                        </span>
                        @if($unreadNotifs->count() > 0)
                            <span class="notif-badge">{{ $unreadNotifs->count() > 9 ? '9+' : $unreadNotifs->count() }}</span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end notif-dropdown" aria-labelledby="notifDropdown">

                        {{-- Header --}}
                        <li>
                            <div class="notif-head">
                                <div class="notif-head-left">
                                    <div class="notif-head-icon"><i class="bi bi-bell-fill"></i></div>
                                    <div>
                                        <p class="notif-head-title">Pemberitahuan</p>
                                        <p class="notif-head-sub">Aktivitas terbaru sistem</p>
                                    </div>
                                </div>
                                @if($unreadNotifs->count() > 0)
                                    <span class="notif-count-pill">
                                        <span class="notif-count-dot"></span>
                                        {{ $unreadNotifs->count() }} baru
                                    </span>
                                @endif
                            </div>
                        </li>

                        {{-- List --}}
                        <li>
                            <div class="notif-body">
                                @forelse($unreadNotifs->take(5) as $notif)
                                    @php
                                        $rawIcon = $notif->data['icon'] ?? 'bi-exclamation-triangle';
                                        $iconClass = match(true) {
                                            str_contains($rawIcon, 'exclamation') || str_contains($rawIcon, 'shield') => 'notif-icon-warn',
                                            str_contains($rawIcon, 'x-circle')    || str_contains($rawIcon, 'dash')   => 'notif-icon-danger',
                                            str_contains($rawIcon, 'check')       || str_contains($rawIcon, 'person-plus') => 'notif-icon-success',
                                            default => 'notif-icon-info',
                                        };
                                    @endphp
                                    <a href="{{ route('rfid.notifikasi.read', $notif->id) }}" class="notif-item unread">
                                        <div class="notif-icon {{ $iconClass }}">
                                            <i class="bi {{ $rawIcon }}"></i>
                                        </div>
                                        <div class="notif-content">
                                            <div class="notif-title">
                                                {{ $notif->data['nama_siswa'] ?? 'Siswa' }}
                                                <span class="notif-kelas">{{ $notif->data['nama_kelas'] ?? '-' }}</span>
                                            </div>
                                            <p class="notif-msg">{{ $notif->data['pesan'] ?? '' }}</p>
                                            <div class="notif-meta">
                                                <span class="notif-time">
                                                    <i class="bi bi-clock"></i>
                                                    {{ $notif->created_at->diffForHumans() }}
                                                </span>
                                                <span class="notif-unread-dot"></span>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="notif-empty">
                                        <div class="notif-empty-icon"><i class="bi bi-bell-slash"></i></div>
                                        <p class="notif-empty-title">Semua sudah terbaca</p>
                                        <p class="notif-empty-sub">Tidak ada pemberitahuan baru saat ini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </li>

                        {{-- Footer --}}
                        <li>
                            <div class="notif-footer">
                                <a href="{{ route('notifikasi.index') }}" class="notif-footer-link">
                                    Lihat semua notifikasi
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </li>

                    </ul>
                </div>
            @endif

            {{-- ── Profile ── --}}
            <div class="nav-item dropdown">
                <a class="profile-trigger"
                   href="#"
                   data-bs-toggle="dropdown"
                   data-bs-display="static"
                   aria-expanded="false"
                   id="profileDropdown">
                    <img src="{{ asset('image/user.png') }}" alt="Profile" class="profile-avatar">
                    <div class="profile-info">
                        <div class="profile-username">{{ $user->name }}</div>
                        <span class="profile-role-pill {{ $roleBadgeClass }}">
                            <i class="bi {{ $roleIcon }}"></i>
                            {{ $roleLabel }}
                        </span>
                    </div>
                    <i class="bi bi-chevron-down profile-caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-end profile-dropdown">
                    <li>
                        <div class="dd-profile-head">
                            <div class="dd-avatar-wrap">
                                <img src="{{ asset('image/user.png') }}" alt="Profile" class="dd-avatar">
                                <span class="dd-status-dot"></span>
                            </div>
                            <p class="dd-name">{{ $user->name }}</p>
                            <span class="dd-role-badge">
                                <i class="bi {{ $roleIcon }}"></i>
                                {{ $roleLabel }}
                            </span>
                        </div>
                    </li>
                    <li>
                        <div class="dd-body">
                            @if($user->role === 'superAdmin')
                                <a class="dd-item" href="#">
                                    <span class="dd-item-icon dd-icon-blue"><i class="bi bi-shield-lock-fill"></i></span>
                                    Manage System
                                </a>
                            @elseif($user->role === 'admin')
                                <a class="dd-item" href="#">
                                    <span class="dd-item-icon dd-icon-gray"><i class="bi bi-person-gear"></i></span>
                                    Profile Saya
                                </a>
                            @elseif($user->role === 'guru')
                                <a class="dd-item" href="#">
                                    <span class="dd-item-icon dd-icon-green"><i class="bi bi-journal-text"></i></span>
                                    Laporan Saya
                                </a>
                            @endif
                            <hr class="dd-divider dropdown-divider">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dd-item dd-item-logout">
                                    <span class="dd-item-icon dd-icon-red"><i class="bi bi-box-arrow-right"></i></span>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const profileTrigger = document.getElementById('profileDropdown');
    if (profileTrigger) {
        profileTrigger.addEventListener('show.bs.dropdown', () => profileTrigger.classList.add('show'));
        profileTrigger.addEventListener('hide.bs.dropdown', () => profileTrigger.classList.remove('show'));
    }
    const notifTrigger = document.getElementById('notifDropdown');
    if (notifTrigger) {
        notifTrigger.addEventListener('show.bs.dropdown', () => notifTrigger.classList.add('show'));
        notifTrigger.addEventListener('hide.bs.dropdown', () => notifTrigger.classList.remove('show'));
    }
});
</script>