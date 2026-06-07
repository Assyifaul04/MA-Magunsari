@php
    use Illuminate\Support\Facades\Auth;

    /**
     * ┌─────────────────────────────────────────────────────────────────┐
     *  HELPER FUNCTIONS — JANGAN DIUBAH
     *  isActive()    → memberi class 'active' jika route cocok
     *  isExpanded()  → membuka accordion submenu jika route cocok
     * └─────────────────────────────────────────────────────────────────┘
     */
    function isActive($routes)  { return request()->routeIs($routes) ? 'active' : ''; }
    function isExpanded($routes){ return request()->routeIs($routes) ? 'show' : 'collapse'; }

    $role = Auth::user()->role ?? '';
@endphp

{{-- ═══════════════════════════════════════════════════════════════════
     SIDEBAR STYLES
     Ubah warna/font di sini jika perlu mengganti tampilan global.
     Warna ikon ada di bagian "Icon Colour Tokens" di bawah.
═══════════════════════════════════════════════════════════════════════ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap');

    /* ── Shell ───────────────────────────── */
    #sidebar.sidebar {
        background: #ffffff;
        border-right: 1px solid #eaecf3;
        box-shadow: 2px 0 20px rgba(15,23,80,.05);
        font-family: 'DM Sans', sans-serif;
        display: flex;
        flex-direction: column;
        padding-bottom: 0;
    }

    /* ── User Card ───────────────────────── */
    .sb-user {
        margin: 14px 14px 6px;
        padding: 13px 14px;
        background: linear-gradient(130deg,#f0f4ff 0%,#e8eeff 100%);
        border: 1px solid #dce5ff;
        border-radius: 13px;
        display: flex; align-items: center; gap: 11px;
        flex-shrink: 0;
    }
    .sb-avatar {
        width: 38px; height: 38px;
        border-radius: 11px;
        background: linear-gradient(135deg,#4154f1,#6875f5);
        color: #fff; font-size: 16px; font-weight: 700;
        display: grid; place-items: center; flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(65,84,241,.3);
        font-family: 'DM Sans', sans-serif; letter-spacing: -.02em;
    }
    .sb-user-info { min-width: 0; flex: 1; }
    .sb-user-name {
        font-size: 13px; font-weight: 700; color: #1a1d2e;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        line-height: 1.2; letter-spacing: -.01em;
    }
    .sb-user-role {
        font-size: 11px; color: #7c85a2;
        display: flex; align-items: center; gap: 4px;
        margin-top: 3px; font-weight: 500;
    }
    .sb-user-role i { color: #22c55e; font-size: 10px; }

    /* ── Role Chip ── warna beda tiap role ── */
    .sb-role-chip {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .04em; padding: 3px 9px;
        border-radius: 50px; margin-left: auto; flex-shrink: 0;
    }
    .sb-role-chip.chip-admin { background: #fff0f0; color: #e03131; }
    .sb-role-chip.chip-guru  { background: #f0fff8; color: #0ca678; }
    .sb-role-chip.chip-super { background: #f3f0ff; color: #7048e8; }

    /* ── Scrollable Nav ──────────────────── */
    .sidebar-nav {
        padding: 6px 0 24px;
        overflow-y: auto; flex: 1;
    }
    .sidebar-nav::-webkit-scrollbar { width: 3px; }
    .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e6f0; border-radius: 4px; }

    /* ── Section Headings ────────────────── */
    .sidebar-nav .nav-heading {
        font-size: 9.5px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 1.5px;
        color: #c2cbdf; padding: 20px 22px 7px; margin: 0;
    }
    .sidebar-nav .nav-heading-divider {
        border: none; border-top: 1px solid #f0f2f8; margin: 2px 18px 4px;
    }

    /* ── Nav Items ───────────────────────── */
    .sidebar-nav .nav-item { list-style: none; }
    .sidebar-nav .nav-link {
        display: flex; align-items: center; gap: 10px;
        font-size: 13.5px; font-weight: 500; color: #5a6382;
        border-radius: 10px; margin: 1px 10px;
        padding: 10px 13px;
        transition: background .18s, color .18s;
        text-decoration: none; position: relative; letter-spacing: -.01em;
    }
    .sidebar-nav .nav-link i:first-child {
        font-size: 16px; width: 20px; text-align: center; flex-shrink: 0;
    }
    .sidebar-nav .nav-link span { flex: 1; }
    .sidebar-nav .nav-link .bi-chevron-down {
        font-size: 10.5px; color: #c2cbdf;
        transition: transform .25s, color .18s; flex-shrink: 0;
    }
    .sidebar-nav .nav-link:not(.collapsed) .bi-chevron-down {
        transform: rotate(180deg); color: #4154f1;
    }
    .sidebar-nav .nav-link:hover { background: #f4f6ff; color: #4154f1; }
    .sidebar-nav .nav-link:not(.collapsed),
    .sidebar-nav .nav-link.active {
        background: #eef1ff; color: #4154f1; font-weight: 600;
    }
    .sidebar-nav .nav-link.active::before,
    .sidebar-nav .nav-link:not(.collapsed)::before {
        content: ''; position: absolute; left: 0; top: 50%;
        transform: translateY(-50%);
        width: 3px; height: 56%;
        background: #4154f1; border-radius: 0 3px 3px 0;
    }

    /* ── Sub-menu ────────────────────────── */
    .sidebar-nav .nav-content { list-style: none; padding: 2px 0 4px; margin: 0; }
    .sidebar-nav .nav-content li { list-style: none; }
    .sidebar-nav .nav-content a {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 500; color: #7c85a2;
        border-radius: 9px; margin: 1px 10px 1px 34px;
        padding: 8px 13px; text-decoration: none;
        transition: background .15s, color .15s, padding-left .15s;
        position: relative; letter-spacing: -.01em;
    }
    .sidebar-nav .nav-content a i.bi-circle {
        font-size: 6px; color: #d0d5e8; flex-shrink: 0;
        transition: color .15s, transform .15s;
    }
    .sidebar-nav .nav-content a:hover {
        background: #f4f6ff; color: #4154f1; padding-left: 18px;
    }
    .sidebar-nav .nav-content a:hover i.bi-circle { color: #4154f1; transform: scale(1.3); }
    .sidebar-nav .nav-content a.active {
        background: #eef1ff; color: #4154f1; font-weight: 700; padding-left: 18px;
    }
    .sidebar-nav .nav-content a.active i.bi-circle { color: #4154f1; }

    /* ══ Icon Colour Tokens ══════════════════
       Tambah token baru jika butuh warna ikon berbeda:
       .icon-namatoken { color: #hexcolor; }
    ════════════════════════════════════════ */
    .icon-dashboard { color: #4154f1; }
    .icon-user      { color: #ef4444; }
    .icon-presensi  { color: #22c55e; }
    .icon-data      { color: #0ea5e9; }
    .icon-master    { color: #f59e0b; }
    .icon-wa        { color: #10b981; }
    .icon-laporan   { color: #8b5cf6; }

    /* ── Footer ──────────────────────────── */
    .sb-footer {
        padding: 12px 18px;
        border-top: 1px solid #f0f2f8;
        display: flex; align-items: center; gap: 8px;
        flex-shrink: 0; background: #fafbfd;
    }
    .sb-footer-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 0 3px rgba(34,197,94,.18);
        flex-shrink: 0;
    }
    .sb-footer-text { font-size: 11px; color: #bcc3d8; font-weight: 500; }
</style>

{{-- ═══════════════════════════════════════════════════════════════════
     ASIDE — komponen sidebar utama
═══════════════════════════════════════════════════════════════════════ --}}
<aside id="sidebar" class="sidebar">

    {{-- ┌─────────────────────────────────────────────────────────────
         USER CARD
         Menampilkan avatar, nama, dan chip role.
         • Untuk mengubah warna chip → edit CSS .chip-admin / .chip-guru / .chip-super
         • Untuk menambah role baru  → tambahkan case di $chipClass & $chipLabel
    └──────────────────────────────────────────────────────────────── --}}
    <div class="sb-user">
        <div class="sb-avatar">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>
        <div class="sb-user-info">
            <div class="sb-user-name">{{ Auth::user()->name ?? 'Pengguna' }}</div>
            <div class="sb-user-role">
                <i class="bi bi-shield-check"></i>
                <span class="text-capitalize">{{ $role }}</span>
            </div>
        </div>
        @php
            $chipClass = match($role) {
                'superAdmin' => 'chip-super',
                'admin'      => 'chip-admin',
                'guru'       => 'chip-guru',
                default      => 'chip-admin',
            };
            $chipLabel = match($role) {
                'superAdmin' => 'Super',
                'admin'      => 'Admin',
                'guru'       => 'Guru',
                default      => $role,
            };
        @endphp
        <span class="sb-role-chip {{ $chipClass }}">{{ $chipLabel }}</span>
    </div>
    {{-- /USER CARD --}}


    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- ===========================================================
        |  SEMUA ROLE — UTAMA
        |  ✏️  Tambah menu yang tampil untuk semua role di sini.
        =========================================================== --}}
        <li class="nav-heading">Utama</li>
        <hr class="nav-heading-divider">

        {{-- Dashboard — route otomatis sesuai role --}}
        <li class="nav-item">
            @php
                $dashRoute = match($role) {
                    'superAdmin' => 'superAdmin.dashboard',
                    'admin'      => 'admin.dashboard',
                    'guru'       => 'guru.dashboard',
                    default      => 'admin.dashboard',
                };
            @endphp
            <a class="nav-link {{ request()->routeIs($dashRoute) ? 'active' : 'collapsed' }}"
               href="{{ route($dashRoute) }}">
                <i class="bi bi-grid icon-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>
        {{-- /SEMUA ROLE --}}


        {{-- ===========================================================
        |  SUPER ADMIN
        |  ✏️  Tambah menu superAdmin baru di antara tag ini.
        |  ⚠️  Jangan hapus @if / @endif pembungkusnya.
        =========================================================== --}}
        @if ($role === 'superAdmin')

            <li class="nav-heading">Administrator</li>
            <hr class="nav-heading-divider">

            {{-- Manajemen User --}}
            {{-- ✏️  Tambah sub-item: copy-paste <li> di dalam <ul id="users-nav"> --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? '' : 'collapsed' }}"
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
                    {{-- ✏️  Tambah sub-item superAdmin di sini --}}
                </ul>
            </li>
            {{-- /Manajemen User --}}

            {{-- ✏️  Tambah menu superAdmin lain di sini --}}

        @endif
        {{-- /SUPER ADMIN --}}


        {{-- ===========================================================
        |  ADMIN
        |  ✏️  Tambah menu admin baru di antara tag ini.
        |  ⚠️  Jangan hapus @if / @endif pembungkusnya.
        =========================================================== --}}
        @if ($role === 'admin')

            {{-- ── Manajemen Absensi ──────────────────── --}}
            <li class="nav-heading">Manajemen Absensi</li>
            <hr class="nav-heading-divider">

            {{-- Presensi --}}
            @php $presensiActive = request()->routeIs('absensi.masuk') || request()->routeIs('absensi.keluar'); @endphp
            <li class="nav-item">
                <a class="nav-link {{ $presensiActive ? '' : 'collapsed' }}"
                   data-bs-target="#absensi-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-upc-scan icon-presensi"></i>
                    <span>Presensi</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="absensi-nav" class="nav-content {{ $presensiActive ? 'show' : 'collapse' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('absensi.masuk') }}" class="{{ isActive('absensi.masuk') }}">
                            <i class="bi bi-circle"></i><span>Masuk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('absensi.keluar') }}" class="{{ isActive('absensi.keluar') }}">
                            <i class="bi bi-circle"></i><span>Pulang</span>
                        </a>
                    </li>
                    {{-- ✏️  Tambah sub-item Presensi di sini --}}
                </ul>
            </li>
            {{-- /Presensi --}}

            {{-- Data Absensi --}}
            @php
                $dataAbsensiActive = request()->routeIs('absensi.hariIni')
                    || request()->routeIs('absensi.byRange')
                    || request()->routeIs('absensi.rekap_bulanan');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $dataAbsensiActive ? '' : 'collapsed' }}"
                   data-bs-target="#data-absensi-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-calendar-check icon-data"></i>
                    <span>Data Absensi</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="data-absensi-nav" class="nav-content {{ $dataAbsensiActive ? 'show' : 'collapse' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('absensi.hariIni') }}" class="{{ isActive('absensi.hariIni') }}">
                            <i class="bi bi-circle"></i><span>Hari Ini</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('absensi.byRange') }}" class="{{ isActive('absensi.byRange') }}">
                            <i class="bi bi-circle"></i><span>By Range</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('absensi.rekap_bulanan') }}" class="{{ isActive('absensi.rekap_bulanan') }}">
                            <i class="bi bi-circle"></i><span>Rekap Bulanan</span>
                        </a>
                    </li>
                    {{-- ✏️  Tambah sub-item Data Absensi di sini --}}
                </ul>
            </li>
            {{-- /Data Absensi --}}

            {{-- ── Konfigurasi Sistem ─────────────────── --}}
            <li class="nav-heading">Konfigurasi Sistem</li>
            <hr class="nav-heading-divider">

            {{-- Master Data --}}
            @php
                $masterActive = request()->routeIs('siswa.*')
                    || request()->routeIs('kelas.*')
                    || request()->routeIs('pengaturan.*')
                    || request()->routeIs('orangtua.*')
                    || request()->routeIs('guru.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $masterActive ? '' : 'collapsed' }}"
                   data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-database icon-master"></i>
                    <span>Master Data</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="master-nav" class="nav-content {{ $masterActive ? 'show' : 'collapse' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('siswa.index') }}" class="{{ isActive('siswa.*') }}">
                            <i class="bi bi-circle"></i><span>Siswa</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru.index') }}" class="{{ isActive('guru.*') }}">
                            <i class="bi bi-circle"></i><span>Guru</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kelas.index') }}" class="{{ isActive('kelas.*') }}">
                            <i class="bi bi-circle"></i><span>Kelas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orangtua.index') }}" class="{{ isActive('orangtua.*') }}">
                            <i class="bi bi-circle"></i><span>Orang Tua</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pengaturan.edit') }}" class="{{ isActive('pengaturan.*') }}">
                            <i class="bi bi-circle"></i><span>Pengaturan</span>
                        </a>
                    </li>
                    {{-- ✏️  Tambah sub-item Master Data di sini --}}
                </ul>
            </li>
            {{-- /Master Data --}}

            {{-- WhatsApp --}}
            @php $waActive = request()->routeIs('templatewa.*') || request()->routeIs('notifikasiwa.*'); @endphp
            <li class="nav-item">
                <a class="nav-link {{ $waActive ? '' : 'collapsed' }}"
                   data-bs-target="#wa-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-whatsapp icon-wa"></i>
                    <span>WhatsApp</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="wa-nav" class="nav-content {{ $waActive ? 'show' : 'collapse' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('templatewa.index') }}" class="{{ isActive('templatewa.*') }}">
                            <i class="bi bi-circle"></i><span>Template Pesan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('notifikasiwa.index') }}" class="{{ isActive('notifikasiwa.*') }}">
                            <i class="bi bi-circle"></i><span>Log WhatsApp</span>
                        </a>
                    </li>
                    {{-- ✏️  Tambah sub-item WhatsApp di sini --}}
                </ul>
            </li>
            {{-- /WhatsApp --}}

            {{-- ✏️  Tambah menu admin lain di sini --}}

        @endif
        {{-- /ADMIN --}}


        {{-- ===========================================================
        |  GURU
        |  ✏️  Tambah menu guru baru di antara tag ini.
        |  ⚠️  Jangan hapus @if / @endif pembungkusnya.
        =========================================================== --}}
        @if ($role === 'guru')

            <li class="nav-heading">Menu Guru</li>
            <hr class="nav-heading-divider">

             {{-- Daftar Siswa — link langsung (tanpa sub-menu) --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('guru.siswa.*') ? 'active' : 'collapsed' }}"
                   href="{{ route('guru.siswa.index') }}">
                    <i class="bi bi-people icon-presensi"></i>
                    <span>Daftar Siswa</span>
                </a>
            </li>
            {{-- /Daftar Siswa --}}

            {{-- Laporan Siswa --}}
            @php
                $laporanActive = request()->routeIs('guru.absensi.hari-ini')
                    || request()->routeIs('guru.absensi.rekap');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $laporanActive ? '' : 'collapsed' }}"
                   data-bs-target="#laporan-guru-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal-text icon-laporan"></i>
                    <span>Laporan Siswa</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="laporan-guru-nav" class="nav-content {{ $laporanActive ? 'show' : 'collapse' }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('guru.absensi.hari-ini') }}" class="{{ isActive('guru.absensi.hari-ini') }}">
                            <i class="bi bi-circle"></i><span>Absensi Kelas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru.absensi.rekap') }}" class="{{ isActive('guru.absensi.rekap') }}">
                            <i class="bi bi-circle"></i><span>Rekap Bulanan</span>
                        </a>
                    </li>
                    {{-- ✏️  Tambah sub-item Laporan di sini --}}
                </ul>
            </li>
            {{-- /Laporan Siswa --}}

            {{-- ✏️  Tambah menu guru lain di sini --}}

        @endif
        {{-- /GURU --}}

    </ul>
    {{-- /sidebar-nav --}}


    {{-- ┌─────────────────────────────────────────────────────────────
         FOOTER
         Ganti teks "Sistem Absensi" sesuai nama aplikasi jika perlu.
    └──────────────────────────────────────────────────────────────── --}}
    <div class="sb-footer">
        <div class="sb-footer-dot"></div>
        <span class="sb-footer-text">Sistem Absensi &bull; Online</span>
    </div>

</aside>