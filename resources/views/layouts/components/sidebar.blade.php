@php
    use Illuminate\Support\Facades\Auth;

    function isActive($routes)
    {
        return request()->routeIs($routes) ? 'active' : '';
    }

    function isExpanded($routes)
    {
        return request()->routeIs($routes) ? 'show' : 'collapse';
    }
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap');

    /* ═══════════════════════════════════════
       SIDEBAR SHELL
    ═══════════════════════════════════════ */
    #sidebar.sidebar {
        background: #ffffff;
        border-right: 1px solid #eaecf3;
        box-shadow: 2px 0 20px rgba(15, 23, 80, 0.05);
        font-family: 'DM Sans', sans-serif;
        display: flex;
        flex-direction: column;
        padding-bottom: 0;
    }

    /* ── User Card ───────────────────────── */
    .sb-user {
        margin: 14px 14px 6px;
        padding: 13px 14px;
        background: linear-gradient(130deg, #f0f4ff 0%, #e8eeff 100%);
        border: 1px solid #dce5ff;
        border-radius: 13px;
        display: flex;
        align-items: center;
        gap: 11px;
        flex-shrink: 0;
    }
    .sb-avatar {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: linear-gradient(135deg, #4154f1, #6875f5);
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(65, 84, 241, .3);
        font-family: 'DM Sans', sans-serif;
        letter-spacing: -.02em;
    }
    .sb-user-info { min-width: 0; flex: 1; }
    .sb-user-name {
        font-size: 13px;
        font-weight: 700;
        color: #1a1d2e;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.2;
        letter-spacing: -.01em;
    }
    .sb-user-role {
        font-size: 11px;
        color: #7c85a2;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 3px;
        font-weight: 500;
    }
    .sb-user-role i {
        color: #22c55e;
        font-size: 10px;
    }

    /* ── Scrollable Nav Area ─────────────── */
    .sidebar-nav {
        padding: 6px 0 24px;
        overflow-y: auto;
        flex: 1;
    }
    .sidebar-nav::-webkit-scrollbar { width: 3px; }
    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #e2e6f0;
        border-radius: 4px;
    }

    /* ── Section Headings ────────────────── */
    .sidebar-nav .nav-heading {
        font-family: 'DM Sans', sans-serif;
        font-size: 9.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #c2cbdf;
        padding: 20px 22px 7px;
        margin: 0;
    }

    /* ── Nav Items ───────────────────────── */
    .sidebar-nav .nav-item { list-style: none; }

    .sidebar-nav .nav-link {
        font-family: 'DM Sans', sans-serif;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        font-weight: 500;
        color: #5a6382;
        border-radius: 10px;
        margin: 1px 10px;
        padding: 10px 13px;
        transition: background .18s ease, color .18s ease;
        text-decoration: none;
        position: relative;
        letter-spacing: -.01em;
    }
    .sidebar-nav .nav-link i:first-child {
        font-size: 16px;
        width: 20px;
        text-align: center;
        flex-shrink: 0;
    }
    .sidebar-nav .nav-link span { flex: 1; }

    /* chevron */
    .sidebar-nav .nav-link .bi-chevron-down {
        font-size: 10.5px;
        color: #c2cbdf;
        transition: transform .25s ease, color .18s ease;
        flex-shrink: 0;
    }
    .sidebar-nav .nav-link:not(.collapsed) .bi-chevron-down {
        transform: rotate(180deg);
        color: #4154f1;
    }

    /* hover */
    .sidebar-nav .nav-link:hover {
        background: #f4f6ff;
        color: #4154f1;
    }

    /* active / open parent */
    .sidebar-nav .nav-link:not(.collapsed),
    .sidebar-nav .nav-link.active {
        background: #eef1ff;
        color: #4154f1;
        font-weight: 600;
    }

    /* left accent bar */
    .sidebar-nav .nav-link.active::before,
    .sidebar-nav .nav-link:not(.collapsed)::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        transform: translateY(-50%);
        width: 3px; height: 56%;
        background: #4154f1;
        border-radius: 0 3px 3px 0;
    }

    /* ── Sub-menu ────────────────────────── */
    .sidebar-nav .nav-content {
        list-style: none;
        padding: 2px 0 4px;
        margin: 0;
    }
    .sidebar-nav .nav-content li { list-style: none; }

    .sidebar-nav .nav-content a {
        font-family: 'DM Sans', sans-serif;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #7c85a2;
        border-radius: 9px;
        margin: 1px 10px 1px 34px;
        padding: 8px 13px;
        text-decoration: none;
        transition: background .15s ease, color .15s ease, padding-left .15s ease;
        position: relative;
        letter-spacing: -.01em;
    }
    .sidebar-nav .nav-content a i.bi-circle {
        font-size: 6px;
        color: #d0d5e8;
        flex-shrink: 0;
        transition: color .15s, transform .15s;
    }
    .sidebar-nav .nav-content a:hover {
        background: #f4f6ff;
        color: #4154f1;
        padding-left: 18px;
    }
    .sidebar-nav .nav-content a:hover i.bi-circle {
        color: #4154f1;
        transform: scale(1.3);
    }
    .sidebar-nav .nav-content a.active {
        background: #eef1ff;
        color: #4154f1;
        font-weight: 700;
        padding-left: 18px;
    }
    .sidebar-nav .nav-content a.active i.bi-circle { color: #4154f1; }

    /* ── Icon Colours ────────────────────── */
    .icon-dashboard { color: #4154f1; }
    .icon-user      { color: #ef4444; }
    .icon-presensi  { color: #22c55e; }
    .icon-data      { color: #0ea5e9; }
    .icon-master    { color: #f59e0b; }
    .icon-wa        { color: #10b981; }

    /* ── Sidebar Footer ──────────────────── */
    .sb-footer {
        padding: 12px 18px;
        border-top: 1px solid #f0f2f8;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        background: #fafbfd;
    }
    .sb-footer-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, .18);
        flex-shrink: 0;
    }
    .sb-footer-text {
        font-family: 'DM Sans', sans-serif;
        font-size: 11px;
        color: #bcc3d8;
        font-weight: 500;
    }
</style>

<aside id="sidebar" class="sidebar">

    <!-- User Widget -->
    <div class="sb-user">
        <div class="sb-avatar">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>
        <div class="sb-user-info">
            <div class="sb-user-name">{{ Auth::user()->name ?? 'Pengguna' }}</div>
            <div class="sb-user-role">
                <i class="bi bi-shield-check"></i>
                <span class="text-capitalize">{{ Auth::user()->role }}</span>
            </div>
        </div>
    </div>

    <!-- Nav -->
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-heading">Utama</li>

        <li class="nav-item">
            @if (Auth::user()->role === 'superAdmin')
                <a class="nav-link {{ isActive('superAdmin.dashboard') ? '' : 'collapsed' }}"
                   href="{{ route('superAdmin.dashboard') }}">
                    <i class="bi bi-grid icon-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            @elseif (Auth::user()->role === 'admin')
                <a class="nav-link {{ isActive('admin.dashboard') ? '' : 'collapsed' }}"
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid icon-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            @elseif (Auth::user()->role === 'guru')
                <a class="nav-link {{ isActive('guru.dashboard') ? '' : 'collapsed' }}"
                   href="{{ route('guru.dashboard') }}">
                    <i class="bi bi-grid icon-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            @endif
        </li>

        <!-- SuperAdmin -->
        @if (Auth::user()->role === 'superAdmin')
            <li class="nav-heading">Administrator</li>
            <li class="nav-item">
                <a class="nav-link {{ isActive('users.*') ? '' : 'collapsed' }}"
                   data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-person-gear icon-user"></i>
                    <span>Manajemen User</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="users-nav" class="nav-content {{ isExpanded('users.*') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('users.index') }}" class="{{ isActive('users.index') }}">
                            <i class="bi bi-circle"></i><span>Users</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <!-- Admin -->
        @if (Auth::user()->role === 'admin')

            <li class="nav-heading">Manajemen Absensi</li>

            <li class="nav-item">
                @php
                    $isPresensiActive = isActive('absensi.masuk') || isActive('absensi.pulang') || isActive('absensi.izin');
                    $isPresensiExpanded = $isPresensiActive ? 'show' : 'collapse';
                @endphp
                <a class="nav-link {{ $isPresensiActive ? '' : 'collapsed' }}"
                   data-bs-target="#absensi-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-upc-scan icon-presensi"></i>
                    <span>Presensi</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="absensi-nav" class="nav-content {{ $isPresensiExpanded }}" data-bs-parent="#sidebar-nav">
                    <li><a href="{{ route('absensi.masuk') }}"  class="{{ isActive('absensi.masuk') }}"><i class="bi bi-circle"></i><span>Masuk</span></a></li>
                    <li><a href="{{ route('absensi.keluar') }}" class="{{ isActive('absensi.keluar') }}"><i class="bi bi-circle"></i><span>Pulang</span></a></li>
                    {{-- <li><a href="{{ route('absensi.izin') }}" class="{{ isActive('absensi.izin') }}"><i class="bi bi-circle"></i><span>Izin</span></a></li> --}}
                </ul>
            </li>

            <li class="nav-item">
                @php
                    $isDataAbsensiActive = isActive('absensi.hariIni') || isActive('absensi.byRange') || isActive('absensi.rekap_bulanan');
                    $isDataAbsensiExpanded = $isDataAbsensiActive ? 'show' : 'collapse';
                @endphp
                <a class="nav-link {{ $isDataAbsensiActive ? '' : 'collapsed' }}"
                   data-bs-target="#data-absensi-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-calendar-check icon-data"></i>
                    <span>Data Absensi</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="data-absensi-nav" class="nav-content {{ $isDataAbsensiExpanded }}" data-bs-parent="#sidebar-nav">
                    <li><a href="{{ route('absensi.hariIni') }}"      class="{{ isActive('absensi.hariIni') }}"><i class="bi bi-circle"></i><span>Hari Ini</span></a></li>
                    <li><a href="{{ route('absensi.byRange') }}"       class="{{ isActive('absensi.byRange') }}"><i class="bi bi-circle"></i><span>By Range</span></a></li>
                    <li><a href="{{ route('absensi.rekap_bulanan') }}" class="{{ isActive('absensi.rekap_bulanan') }}"><i class="bi bi-circle"></i><span>Rekap Bulanan</span></a></li>
                </ul>
            </li>

            <li class="nav-heading">Konfigurasi Sistem</li>

            <li class="nav-item">
                @php
                    $isMasterActive = isActive('siswa.*') || isActive('kelas.*') || isActive('pengaturan.*') || isActive('orangtua.*');
                    $isMasterExpanded = $isMasterActive ? 'show' : 'collapse';
                @endphp
                <a class="nav-link {{ $isMasterActive ? '' : 'collapsed' }}"
                   data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-database icon-master"></i>
                    <span>Master Data</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="master-nav" class="nav-content {{ $isMasterExpanded }}" data-bs-parent="#sidebar-nav">
                    <li><a href="{{ route('siswa.index') }}"     class="{{ isActive('siswa.*') }}"><i class="bi bi-circle"></i><span>Siswa</span></a></li>
                    <li><a href="{{ route('kelas.index') }}"     class="{{ isActive('kelas.*') }}"><i class="bi bi-circle"></i><span>Kelas</span></a></li>
                    <li><a href="{{ route('orangtua.index') }}"  class="{{ isActive('orangtua.*') }}"><i class="bi bi-circle"></i><span>Orang Tua</span></a></li>
                    <li><a href="{{ route('pengaturan.edit') }}" class="{{ isActive('pengaturan.*') }}"><i class="bi bi-circle"></i><span>Pengaturan</span></a></li>
                </ul>
            </li>

            <li class="nav-item">
                @php
                    $isWaActive = isActive('templatewa.*') || isActive('notifikasiwa.*');
                    $isWaExpanded = $isWaActive ? 'show' : 'collapse';
                @endphp
                <a class="nav-link {{ $isWaActive ? '' : 'collapsed' }}"
                   data-bs-target="#wa-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-whatsapp icon-wa"></i>
                    <span>WhatsApp</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="wa-nav" class="nav-content {{ $isWaExpanded }}" data-bs-parent="#sidebar-nav">
                    <li><a href="{{ route('templatewa.index') }}"   class="{{ isActive('templatewa.*') }}"><i class="bi bi-circle"></i><span>Template Pesan</span></a></li>
                    <li><a href="{{ route('notifikasiwa.index') }}" class="{{ isActive('notifikasiwa.*') }}"><i class="bi bi-circle"></i><span>Log WhatsApp</span></a></li>
                </ul>
            </li>

        @endif

        <!-- Guru -->
        @if (Auth::user()->role === 'guru')
            <li class="nav-heading">Menu Guru</li>
            {{-- Tambahkan menu khusus guru di sini kalau ada --}}
        @endif

    </ul>

    <!-- Footer -->
    <div class="sb-footer">
        <div class="sb-footer-dot"></div>
        <span class="sb-footer-text">Sistem Absensi &bull; Online</span>
    </div>

</aside>