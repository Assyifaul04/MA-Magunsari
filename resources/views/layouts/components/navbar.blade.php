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
        border-bottom: 1px solid #eaecf3;
        box-shadow: 0 1px 0 #eaecf3, 0 4px 16px rgba(15,23,80,.05);
        height: 64px;
        padding: 0 24px;
        z-index: 997;
    }

    .header-inner {
        display: flex;
        align-items: center;
        width: 100%;
        height: 100%;
        gap: 0;
    }

    /* ── Logo ─────────────────────────────── */
    .header-logo {
        display: flex;
        align-items: center;
        gap: 11px;
        text-decoration: none;
        flex-shrink: 0;
        margin-right: 4px;
    }
    .header-logo-img {
        height: 36px;
        width: 36px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(65,84,241,.18);
    }
    .header-logo-text {
        display: none;
        flex-direction: column;
        line-height: 1.2;
    }
    @media (min-width: 992px) {
        .header-logo-text { display: flex; }
    }
    .header-logo-name {
        font-size: .875rem;
        font-weight: 700;
        color: #1a1d2e;
        white-space: nowrap;
        letter-spacing: -.01em;
    }
    .header-logo-sub {
        font-size: .65rem;
        font-weight: 600;
        color: #3b5bdb;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-top: 1px;
    }

    /* ── Divider ──────────────────────────── */
    .header-divider {
        width: 1px;
        height: 28px;
        background: #eaecf3;
        margin: 0 16px;
        flex-shrink: 0;
    }

    /* ── Sidebar Toggle ───────────────────── */
    .toggle-sidebar-btn {
        font-size: 1.2rem;
        color: #7c85a2;
        cursor: pointer;
        padding: 7px 9px;
        border-radius: 9px;
        border: 1px solid transparent;
        transition: all .18s ease;
        line-height: 1;
        display: grid;
        place-items: center;
    }
    .toggle-sidebar-btn:hover {
        background: #f0f3ff;
        border-color: #dce5ff;
        color: #3b5bdb;
    }

    /* ── Nav Right ────────────────────────── */
    .header-nav-right {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-left: auto;
    }

    /* ── Profile Trigger ──────────────────── */
    .profile-trigger {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 5px 12px 5px 6px;
        border-radius: 50px;
        border: 1.5px solid #eaecf3;
        background: #f8f9fc;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s ease;
        position: relative;
    }
    .profile-trigger:hover,
    .profile-trigger.show {
        border-color: #3b5bdb;
        box-shadow: 0 0 0 3px rgba(59,91,219,.1);
        background: #fff;
    }
    .profile-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #fff;
        box-shadow: 0 1px 5px rgba(0,0,0,.12);
    }
    .profile-info { display: none; }
    @media (min-width: 768px) { .profile-info { display: block; } }
    .profile-username {
        font-size: .8rem;
        font-weight: 700;
        color: #1a1d2e;
        white-space: nowrap;
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        letter-spacing: -.01em;
    }
    .profile-role-pill {
        font-size: .6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        padding: 2px 8px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        margin-top: 2px;
    }
    .role-super { background: #fff0f6; color: #c2255c; }
    .role-admin { background: #eef2ff; color: #3b5bdb; }
    .role-guru  { background: #e6fcf5; color: #0ca678; }

    .profile-caret {
        font-size: .65rem;
        color: #bcc3d8;
        transition: transform .22s ease;
        flex-shrink: 0;
    }
    .profile-trigger.show .profile-caret {
        transform: rotate(180deg);
        color: #3b5bdb;
    }

    /* ── Dropdown ─────────────────────────── */
    .profile-dropdown {
        min-width: 252px;
        border: 1px solid #eaecf3;
        border-radius: 16px;
        box-shadow:
            0 0 0 1px rgba(0,0,0,.03),
            0 8px 24px rgba(15,23,80,.10),
            0 2px 6px rgba(15,23,80,.06);
        overflow: hidden;
        padding: 0;
        margin-top: 10px !important;
    }

    /* Dropdown header */
    .dd-profile-head {
        padding: 20px 16px 16px;
        background: linear-gradient(135deg, #1c3faa 0%, #3b5bdb 55%, #4f75ff 100%);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .dd-profile-head::before {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 130px; height: 130px;
        background: rgba(255,255,255,.06);
        border-radius: 50%;
    }
    .dd-profile-head::after {
        content: '';
        position: absolute;
        left: -20px; bottom: -30px;
        width: 90px; height: 90px;
        background: rgba(255,255,255,.05);
        border-radius: 50%;
    }
    .dd-avatar-wrap {
        position: relative;
        display: inline-block;
        margin-bottom: 10px;
    }
    .dd-avatar {
        width: 58px; height: 58px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,.45);
        box-shadow: 0 4px 14px rgba(0,0,0,.22);
        position: relative;
    }
    .dd-status-dot {
        position: absolute;
        bottom: 2px; right: 2px;
        width: 13px; height: 13px;
        background: #51cf66;
        border-radius: 50%;
        border: 2.5px solid rgba(255,255,255,.9);
        box-shadow: 0 1px 4px rgba(0,0,0,.2);
    }
    .dd-name {
        font-size: .88rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 6px;
        position: relative;
        letter-spacing: -.01em;
    }
    .dd-role-badge {
        font-size: .62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        padding: 4px 12px;
        border-radius: 50px;
        background: rgba(255,255,255,.18);
        color: rgba(255,255,255,.95);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        position: relative;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,.15);
    }

    /* Dropdown body */
    .dd-body { padding: 8px 0 6px; }

    .dd-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 9px 16px;
        font-size: .82rem;
        font-weight: 600;
        color: #1a1d2e;
        text-decoration: none;
        transition: background .15s ease;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        letter-spacing: -.01em;
    }
    .dd-item:hover { background: #f6f8ff; color: #1a1d2e; }
    .dd-item-icon {
        width: 32px; height: 32px;
        border-radius: 9px;
        display: grid;
        place-items: center;
        font-size: .82rem;
        flex-shrink: 0;
    }
    .dd-icon-blue  { background: #eef2ff; color: #3b5bdb; }
    .dd-icon-gray  { background: #f3f4f8; color: #6c757d; }
    .dd-icon-green { background: #e6fcf5; color: #0ca678; }
    .dd-icon-red   { background: #fff5f5; color: #e03131; }

    .dd-divider { border-color: #f3f4f8; margin: 4px 0; }

    .dd-item-logout { color: #e03131; }
    .dd-item-logout:hover { background: #fff5f5; color: #c92a2a; }
</style>

<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="header-inner">

        <!-- Logo -->
        <a href="{{ $dashRoute }}" class="header-logo">
            <img src="{{ asset('image/logo.png') }}" alt="Logo MAS Nurul Huda" class="header-logo-img">
            <div class="header-logo-text">
                <span class="header-logo-name">MAS Nurul Huda</span>
                <span class="header-logo-sub">Sistem Absensi Digital</span>
            </div>
        </a>

        <!-- Divider -->
        <div class="header-divider"></div>

        <!-- Sidebar Toggle -->
        <i class="bi bi-list toggle-sidebar-btn"></i>

        <!-- Right Nav -->
        <div class="header-nav-right">

            <!-- Profile Dropdown -->
            <div class="nav-item dropdown">
                <a class="profile-trigger"
                   href="#"
                   data-bs-toggle="dropdown"
                   aria-expanded="false"
                   id="profileDropdown">
                    <img src="{{ asset('assets/img/profile-img.jpg') }}"
                         alt="Profile"
                         class="profile-avatar">
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

                    <!-- Header -->
                    <li>
                        <div class="dd-profile-head">
                            <div class="dd-avatar-wrap">
                                <img src="{{ asset('assets/img/profile-img.jpg') }}"
                                     alt="Profile"
                                     class="dd-avatar">
                                <span class="dd-status-dot"></span>
                            </div>
                            <p class="dd-name">{{ $user->name }}</p>
                            <span class="dd-role-badge">
                                <i class="bi {{ $roleIcon }}"></i>
                                {{ $roleLabel }}
                            </span>
                        </div>
                    </li>

                    <!-- Menu Items -->
                    <li>
                        <div class="dd-body">
                            @if ($user->role === 'superAdmin')
                                <a class="dd-item" href="#">
                                    <span class="dd-item-icon dd-icon-blue">
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </span>
                                    Manage System
                                </a>
                            @elseif ($user->role === 'admin')
                                <a class="dd-item" href="#">
                                    <span class="dd-item-icon dd-icon-gray">
                                        <i class="bi bi-person-gear"></i>
                                    </span>
                                    Profile Saya
                                </a>
                            @elseif ($user->role === 'guru')
                                <a class="dd-item" href="#">
                                    <span class="dd-item-icon dd-icon-green">
                                        <i class="bi bi-journal-text"></i>
                                    </span>
                                    Laporan Saya
                                </a>
                            @endif

                            <hr class="dd-divider dropdown-divider">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dd-item dd-item-logout">
                                    <span class="dd-item-icon dd-icon-red">
                                        <i class="bi bi-box-arrow-right"></i>
                                    </span>
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
        const trigger = document.getElementById('profileDropdown');
        if (trigger) {
            trigger.addEventListener('show.bs.dropdown', () => trigger.classList.add('show'));
            trigger.addEventListener('hide.bs.dropdown', () => trigger.classList.remove('show'));
        }
    });
</script>